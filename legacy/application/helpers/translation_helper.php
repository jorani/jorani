<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global translation helper for Gettext / Symfony Translator
 */
if (!function_exists('__')) {
    /**
     * Short alias for translation
     * @param string $id         The message ID (English string in your PO)
     * @param array  $parameters Placeholders like ['%name%' => 'John']
     * @param string $domain     The PO domain (default is 'messages')
     * @return string
     */
    function __(string $id, array $parameters = [], string $domain = 'messages'): string
    {
        // Get the CodeIgniter singleton instance
        $ci =& get_instance();

        // Check if the current controller has the translation method
        // (This works because your MY_Controller uses the TranslationBridge trait)
        if (method_exists($ci, '__')) {
            return $ci->__($id, $parameters, $domain);
        }

        // Fallback: if the trait isn't loaded, return the ID itself
        return $id;
    }
}

/**
 * Even shorter alias: t()
 */
if (!function_exists('t')) {
    function t(string $id, array $parameters = [], string $domain = 'messages'): string
    {
        return __($id, $parameters, $domain);
    }
}
