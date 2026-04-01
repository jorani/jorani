<?php
namespace App\Traits;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\PoFileLoader;

/**
 * Trait to handle Gettext (.po) translations using Symfony components.
 * Supports regional locales (en_GB, fr_CA) with automatic fallback to base language.
 *
 * Performance strategy
 * ─────────────────────
 * Parsing .po files on every request is expensive (I/O + tokenisation).
 * This trait compiles each locale's catalogues into a plain PHP array file stored
 * in APPPATH/cache/translations/<locale>.php.  PHP files are loaded by OPcache on
 * subsequent requests, making translation lookup essentially free.
 *
 * Cache invalidation: a fingerprint (md5 of "path@mtime" for every .po file) is
 * embedded in the cache file.  The cache is transparently regenerated whenever a
 * source .po file is modified, deleted, or added.
 */
trait TranslationBridge
{
    /**
     * In-process singleton — avoids re-initialisation within a single HTTP request.
     * @var Translator|null
     */
    private static ?Translator $translatorInstance = null;

    // =========================================================================
    // Public API
    // =========================================================================

    /**
     * Translate a message with placeholder and domain support.
     *
     * @param string $id         The source string (msgid)
     * @param array  $parameters Key-value pairs for placeholders (e.g. ['%count%' => 5])
     * @param string $domain     The translation domain (default: messages)
     */
    public function __(string $id, array $parameters = [], string $domain = 'messages'): string
    {
        return $this->getTranslator()->trans($id, $parameters, $domain);
    }

    // =========================================================================
    // Translator initialisation
    // =========================================================================

    /**
     * Returns the Symfony Translator, building it on first call.
     * On subsequent calls within the same request the singleton is returned directly.
     */
    protected function getTranslator(): Translator
    {
        if (self::$translatorInstance !== null) {
            return self::$translatorInstance;
        }

        $ci = get_instance();

        // ── 1. Locale resolution ─────────────────────────────────────────────
        $rawLocale  = $ci->session->userdata('language_code') ?: 'en';
        $safeLocale = preg_replace('/[^a-zA-Z_-]/', '', $rawLocale);

        // ── 2. Fallback chain:  e.g. fr_CA → fr → en ────────────────────────
        $fallbacks = ['en'];
        if (str_contains($safeLocale, '_')) {
            $baseLang = explode('_', $safeLocale)[0];
            if ($baseLang !== 'en') {
                array_unshift($fallbacks, $baseLang);
            }
        }
        $localesToLoad = array_unique(array_merge([$safeLocale], $fallbacks));

        // ── 3. Discover .po files (with path-traversal guard) ────────────────
        $translationDir = APPPATH . 'translations';
        $domains        = ['messages', 'validators'];
        $resources      = $this->resolvePoFiles($translationDir, $localesToLoad, $domains);

        // ── 4. Load compiled PHP-array catalogues (cache miss → parse .po) ───
        $catalogues = $this->loadCatalogues($resources, $safeLocale);

        // ── 5. Build Translator from pre-compiled arrays (OPcache-fast) ──────
        self::$translatorInstance = new Translator($safeLocale);
        self::$translatorInstance->addLoader('array', new ArrayLoader());
        self::$translatorInstance->setFallbackLocales($fallbacks);

        foreach ($catalogues as $locale => $domainMessages) {
            foreach ($domainMessages as $domain => $messages) {
                self::$translatorInstance->addResource('array', $messages, $locale, $domain);
            }
        }

        return self::$translatorInstance;
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    /**
     * Discovers and validates .po file paths for all requested locales and domains.
     * Path-traversal attacks are mitigated by comparing realpath() against the
     * resolved translation directory.
     *
     * @param  string   $dir     Absolute path to the translations directory
     * @param  string[] $locales Ordered list of locales to load (main locale first)
     * @param  string[] $domains Translation domains to load
     * @return array<int, array{locale: string, domain: string, path: string, mtime: int}>
     */
    private function resolvePoFiles(string $dir, array $locales, array $domains): array
    {
        $realDir   = realpath($dir);
        $resources = [];

        if ($realDir === false) {
            return $resources; // Translation directory does not exist yet
        }

        foreach ($locales as $locale) {
            foreach ($domains as $domain) {
                $path = $dir . DIRECTORY_SEPARATOR . "{$domain}.{$locale}.po";

                if (!file_exists($path)) {
                    continue;
                }

                $realPath = realpath($path);

                // Security: ensure the resolved path stays inside $dir
                if ($realPath === false || !str_starts_with($realPath, $realDir . DIRECTORY_SEPARATOR)) {
                    continue;
                }

                $resources[] = [
                    'locale' => $locale,
                    'domain' => $domain,
                    'path'   => $realPath,
                    'mtime'  => filemtime($realPath),
                ];
            }
        }

        return $resources;
    }

    /**
     * Returns compiled catalogues, loading them from the PHP-array disk cache when
     * possible or parsing the source .po files and writing the cache on a miss.
     *
     * Cache file layout (APPPATH/cache/translations/<locale>.php):
     * ```php
     * <?php
     * return [
     *     'fingerprint' => '<md5>',
     *     'catalogues'  => [locale => [domain => [msgid => msgstr, ...]]],
     * ];
     * ```
     *
     * @param  array<int, array{locale: string, domain: string, path: string, mtime: int}> $resources
     * @param  string $safeLocale  The primary locale (used as the cache-file name)
     * @return array<string, array<string, array<string, string>>>
     */
    private function loadCatalogues(array $resources, string $safeLocale): array
    {
        $cacheDir  = APPPATH . 'cache/translations';
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $safeLocale . '.php';

        $fingerprint = $this->computeFingerprint($resources);

        // ── Cache HIT ────────────────────────────────────────────────────────
        if (file_exists($cacheFile)) {
            // @include returns the array defined in the PHP file (OPcache picks this up)
            $cached = @include $cacheFile;
            if (is_array($cached) && ($cached['fingerprint'] ?? null) === $fingerprint) {
                return $cached['catalogues'];
            }
        }

        // ── Cache MISS: parse .po → PHP arrays, then persist ─────────────────
        $loader     = new PoFileLoader();
        $catalogues = [];

        foreach ($resources as $r) {
            $catalogue                                    = $loader->load($r['path'], $r['locale'], $r['domain']);
            $catalogues[$r['locale']][$r['domain']] = $catalogue->all($r['domain']);
        }

        $this->writeCatalogueCache($cacheDir, $cacheFile, $fingerprint, $catalogues);

        return $catalogues;
    }

    /**
     * Computes a cache-invalidation fingerprint from the list of resources.
     * The fingerprint changes whenever a file is added, removed, or its mtime changes.
     */
    private function computeFingerprint(array $resources): string
    {
        $parts = array_map(
            static fn(array $r): string => $r['path'] . '@' . $r['mtime'],
            $resources
        );

        return md5(implode('|', $parts));
    }

    /**
     * Persists compiled catalogues to a PHP file atomically (write + rename).
     *
     * Using var_export() produces syntactically valid PHP that OPcache stores in
     * shared memory, making subsequent includes near-instant.
     * The atomic rename prevents another worker from reading a partially written file.
     */
    private function writeCatalogueCache(
        string $dir,
        string $file,
        string $fingerprint,
        array $catalogues
    ): void {
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return; // Cannot create cache directory — degrade gracefully
        }

        $payload = [
            'fingerprint' => $fingerprint,
            'catalogues'  => $catalogues,
        ];

        $php = "<?php\n// Auto-generated by TranslationBridge — do not edit.\nreturn "
             . var_export($payload, true) . ";\n";

        // Write to a PID-specific temp file, then rename (POSIX atomic on Linux)
        $tmp = $file . '.' . getmypid() . '.tmp';
        if (file_put_contents($tmp, $php, LOCK_EX) !== false) {
            rename($tmp, $file);
        }
    }
}
