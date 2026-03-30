<?php
namespace App\Traits;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PoFileLoader;

/**
 * Trait to handle Gettext (.po) translations using Symfony components.
 * Supports regional locales (en_GB, fr_CA) with automatic fallback to base language.
 */
trait TranslationBridge
{
    private static $translatorInstance = null;

    /**
     * Initializes the translator with regional fallbacks and security filters.
     */
    protected function getTranslator(): Translator
    {
        if (self::$translatorInstance === null) {
            $ci =& get_instance();

            // 1. Get locale from session and sanitize
            // Allows alphanumeric, underscores, and hyphens (e.g., 'en_GB', 'fr-CA')
            $rawLocale = $ci->session->userdata('language_code') ?: 'en';
            $safeLocale = preg_replace('/[^a-zA-Z_-]/', '', $rawLocale);

            // 2. Initialize Symfony Translator
            self::$translatorInstance = new Translator($safeLocale);
            self::$translatorInstance->addLoader('po', new PoFileLoader());

            // 3. Configure Fallback mechanism
            // If the locale is 'en_GB', we want to fall back to 'en'.
            $fallbacks = ['en'];
            if (strpos($safeLocale, '_') !== false) {
                $baseLang = explode('_', $safeLocale)[0];
                if ($baseLang !== 'en') {
                    array_unshift($fallbacks, $baseLang);
                }
            }
            self::$translatorInstance->setFallbackLocales($fallbacks);

            // 4. Register Translation Directory
            $translationDir = APPPATH . 'translations';
            $domains = ['messages', 'validators'];

            // 5. Load resources for the main locale AND the fallbacks
            // This allows 'en_GB' to only contain specific overrides for 'en'
            $localesToLoad = array_unique(array_merge([$safeLocale], $fallbacks));

            foreach ($localesToLoad as $loc) {
                foreach ($domains as $domain) {
                    $fileName = "{$domain}.{$loc}.po";
                    $filePath = $translationDir . DIRECTORY_SEPARATOR . $fileName;

                    // Security: Path Traversal Check
                    if (file_exists($filePath) && strpos(realpath($filePath), realpath($translationDir)) === 0) {
                        self::$translatorInstance->addResource('po', $filePath, $loc, $domain);
                    }
                }
            }
        }
        return self::$translatorInstance;
    }

    /**
     * Translate a message with placeholder and domain support.
     * @param string $id         The source string (msgid)
     * @param array  $parameters Key-value pairs for placeholders (e.g., ['%count%' => 5])
     * @param string $domain     The translation domain (default: messages)
     * @return string
     */
    public function __(string $id, array $parameters = [], string $domain = 'messages'): string
    {
        return $this->getTranslator()->trans($id, $parameters, $domain);
    }
}
