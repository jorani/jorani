<?php
/**
 * Utility script that converts PHP array i18n files to a POT file
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

//Include all translation files
$files = scandir('english/');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'index.html') {
        $path = join_paths("english", $file);
        include $path;
    }
}

$strings = array(); //Array of unique strings
//File content
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
$messages .= '"Language: en\n"' . PHP_EOL . PHP_EOL;

//Iterate through the translation strings
foreach ($lang as $message) {
    $message = str_replace("\'", "'", $message);
    $message = str_replace('"', '\"', $message);
    if ((strpos($message, 'http://') === FALSE) && ($message != "")) { //Exclude links to help and empty strings
        if (!array_key_exists($message, $strings)) {
            $strings[$message] = '';
            $messages .= 'msgid "' . $message . '"' . PHP_EOL;
            $messages .= 'msgstr ""' . PHP_EOL . PHP_EOL;
        }
    }
}

//Write the file content
file_put_contents('jorani.pot', $messages);

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
