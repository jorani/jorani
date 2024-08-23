<?php
/**
 * Utility script that converts PHP array i18n files to a PO file
 * This tool is used in order to retrieve a translation that was done from the source files.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.4.4
 */

$source_lang = "english";
$source_langcode = "en";
$target_lang = "khmer";
$target_langcode = "km";

//Include all translation files from source language (eg english)
$files = scandir($source_lang . '/');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'index.html') {
        $path = join_paths($source_lang, $file);
        include $path;
    }
}

$source = $lang;
unset($lang);

//Include all translation files from target language (eg khmer)
$files = scandir($target_lang . '/');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'index.html') {
        $path = join_paths($target_lang, $file);
        include $path;
    }
}

//Get prettier variable names, eg. :
$target = $lang;
unset($lang);
//$source['Leave Management System'] = 'Leave Management System';
//$target['Leave Management System'] = 'Gestion des demandes de congé';


$strings = array(); //Array of unique strings
//File content
$messages = '# ' . PHP_EOL;
$messages = '# Translation strings (' . $target_lang . ') from CI source file' . PHP_EOL;
$messages = '# ' . PHP_EOL;
$messages = 'msgid ""' . PHP_EOL;
$messages .= 'msgstr ""' . PHP_EOL;
$messages .= '"Project-Id-Version: Jorani\n"' . PHP_EOL;
$messages .= '"POT-Creation-Date: \n"' . PHP_EOL;
$messages .= '"PO-Revision-Date: \n"' . PHP_EOL;
$messages .= '"Last-Translator: \n"' . PHP_EOL;
$messages .= '"Language-Team: Jorani <jorani@googlegroups.com>\n"' . PHP_EOL;
$messages .= '"MIME-Version: 1.0\n"' . PHP_EOL;
$messages .= '"Content-Type: text/plain; charset=UTF-8\n"' . PHP_EOL;
$messages .= '"Content-Transfer-Encoding: 8bit\n"' . PHP_EOL;
$messages .= '"Plural-Forms: nplurals=2; plural=(n != 1);\n"' . PHP_EOL;
$messages .= '"Language: ' . $target_langcode . '\n"' . PHP_EOL . PHP_EOL;

//Iterate through the translation strings
foreach ($source as $key => $message) {
    $message = str_replace("\'", "'", $message);
    $message = str_replace('"', '\"', $message);
    if ((strpos($message, 'http://') === FALSE) && ($message != "")) { //Exclude links to help and empty strings
        if (!array_key_exists($message, $strings)) {
            $strings[$message] = '';
            $messages .= 'msgid "' . $message . '"' . PHP_EOL;
            if (array_key_exists($key, $target)) {
                $translated = str_replace("\'", "'", $target[$key]);
                $translated = str_replace('"', '\"', $translated);
                if ($translated != $message) {    //We assume than the two languages are different
                    $messages .= 'msgstr "' . $translated . '"' . PHP_EOL . PHP_EOL;
                } else {
                    $messages .= 'msgstr ""' . PHP_EOL . PHP_EOL;
                }
            } else {
                $messages .= 'msgstr ""' . PHP_EOL . PHP_EOL;
            }
        }
    }
}

//Write the PO file containing all pairs
file_put_contents($target_lang . '.po', $messages);

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
