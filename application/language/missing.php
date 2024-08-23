<?php
/**
 * Utility script that finds missing keys in CI's i18n PHP arrays
 *
 * Usage example: php missing.php > list_missing.txt
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.6.6
 */

echo "Analyze English i18n keys" . PHP_EOL;
$currentPath = realpath(getcwd());
$basePath = join_paths($currentPath, 'english');
echo 'Current Path ' . $currentPath . PHP_EOL;
echo 'Baseline path ' . $basePath . PHP_EOL;
$files = scandir($basePath);
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'index.html') {
        $path = join_paths($basePath, $file);
        include $path;
    }
}
$base = $lang;
echo 'We\'ve found ' . count($base) . '  i18n keys' . PHP_EOL;

echo "Iterate through the supported languages of the application..." . PHP_EOL;
$directories = glob($currentPath . '/*' , GLOB_ONLYDIR);

foreach ($directories as $directory) {
  $langName = basename($directory);
  echo "_______________________________________________" . PHP_EOL;
  echo 'Analyzing ' . $langName . PHP_EOL;
  unset($lang);
  $files = scandir($directory);
  foreach ($files as $file) {
      if ($file != '.' && $file != '..' && $file != 'index.html' &&
            $file != ($langName . '.po')) {
          $path = join_paths($currentPath, $langName, $file);
          include $path;
      }
  }

  //Compute the difference between English and currently scanned language
  $diff = array_diff_key($base, $lang);
  foreach ($diff as $key => $message) {
      echo $key . PHP_EOL;
  }
}


//Internal utility function to join paths
function join_paths() {
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') {
            $paths[] = $arg;
        }
    }
    return preg_replace('#/+#', '/', join(DIRECTORY_SEPARATOR, $paths));
}
