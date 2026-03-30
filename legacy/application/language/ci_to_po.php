<?php
/**
 * CodeIgniter 3 to Gettext PO Converter
 * Extracts English keys as msgid and French values as msgstr.
 */

// --- CONFIGURATION ---
$english_dir = './english';
$french_dir = './english';
$output_file = 'messages.en.po';

// --- UTILITY FUNCTIONS ---

/**
 * Loads a CI3 language file and returns the $lang array.
 * We use a function to isolate the scope of the $lang variable.
 */
function load_lang_file($path)
{
    $lang = [];
    if (file_exists($path)) {
        include($path);
    }
    return $lang;
}

/**
 * Escapes strings for PO format (quotes and newlines)
 */
function escape_po($str)
{
    return str_replace('"', '\"', $str);
}

// --- CORE LOGIC ---

$po_content = "msgid \"\"\n";
$po_content .= "msgstr \"\"\n";
$po_content .= "\"Project-Id-Version: jorani-migration\\n\"\n";
$po_content .= "\"MIME-Version: 1.0\\n\"\n";
$po_content .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
$po_content .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";
$po_content .= '"Plural-Forms: nplurals=2; plural=(n != 1);\n"' . PHP_EOL;
$po_content .= "\"Language: en\\n\"\n\n";

// 1. Scan the English directory for all _lang.php files
$files = glob($english_dir . '/*_lang.php');

$total_keys = 0;

foreach ($files as $en_file_path) {
    $filename = basename($en_file_path);
    $fr_file_path = $french_dir . '/' . $filename;

    echo "Processing: $filename... ";

    // Load arrays
    $en_array = load_lang_file($en_file_path);
    $fr_array = load_lang_file($fr_file_path);

    foreach ($en_array as $key => $en_value) {
        // We use the English value as the ID (as per your PO preference)
        $msgid = escape_po($en_value);

        // Match with French value, fallback to English if missing in French
        $msgstr = isset($fr_array[$key]) ? escape_po($fr_array[$key]) : $msgid;

        //$po_content .= "# File: $filename, Key: $key\n";
        $po_content .= "msgid \"$msgid\"\n";
        $po_content .= "msgstr \"$msgstr\"\n\n";

        $total_keys++;
    }
    echo "Done.\n";
}

// 2. Write to the output file
file_put_contents($output_file, $po_content);

echo "--------------------------------------------------\n";
echo "Success! Created $output_file with $total_keys entries.\n";
echo "Location: " . realpath($output_file) . "\n";
