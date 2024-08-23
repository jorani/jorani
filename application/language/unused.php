<?php
/**
 * Utility script that finds unused strings in CI's i18n PHP arrays
 * This script helps to find mistakes (so don't delete a string before checking if it should be used)
 * Usage example: php unused.php > list_unused.txt
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

echo "Include all translation files" . PHP_EOL;
$files = scandir('english');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'index.html') {
        $path = join_paths("english", $file);
        include $path;
    }
}
echo 'We\'ve found ' . count($lang) . '  i18n keys' . PHP_EOL;

echo "Iterate through the views of the application..." . PHP_EOL;
$path = realpath(join_paths(dirname(getcwd()), 'views'));
echo $path . PHP_EOL;

$directory = new RecursiveDirectoryIterator ($path);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($regex as $file) {
    //echo 'Examing ' .$file[0] . PHP_EOL;
    $content = file_get_contents($file[0]);
    //Search for all possible i18n key
    foreach ($lang as $key => $message) {
        //$usage = "lang('" . $key . "')";
        if (strpos($content, $key) !== false) {
            //Remove the message from the array as it is used
            unset($lang[$key]);
        }
    }
}

echo "Iterate through the controllers of the application..." . PHP_EOL;
$path = realpath(join_paths(dirname(getcwd()), 'controllers'));
echo $path . PHP_EOL;

$directory = new RecursiveDirectoryIterator ($path);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($regex as $file) {
    //echo 'Examing ' .$file[0] . PHP_EOL;
    $content = file_get_contents($file[0]);
    //Search for all possible i18n key
    foreach ($lang as $key => $message) {
        //$usage = "lang('" . $key . "')";
        if (strpos($content, $key) !== false) {
            //Remove the message from the array as it is used
            unset($lang[$key]);
        }
    }
}

echo 'List the ' . count($lang) . ' unused i18n keys' . PHP_EOL;
echo "_______________________________________________" . PHP_EOL;
foreach ($lang as $key => $message) {
    //echo $key . ";" . $message . PHP_EOL;
    echo $key . PHP_EOL;
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
