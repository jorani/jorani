<?php
/**
 * This library helps to deal with language codes, english language names and local names
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.2.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * Helps to deal with language codes, english language names and local names.
 * Maybe that we will replace the switch cases by associative arrays in the future.
 */
class Polyglot {
    /**
     * Default constructor
     */
    public function __construct() {
    }

    /**
     * Explodes a string containing a comma separated list of language codes into an associative array
     * You can pass the config object $this->config->item('languages') as a parameter
     * @param string $languages_list comma separated list of language codes
     * @return array associative array (language code => english language name)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function languages($languages_list) {
        $languages = array();
        $lang_codes = explode(",", $languages_list);
        foreach($lang_codes as $lang_code) {
            $languages[$lang_code] =  $this->code2language($lang_code) ;
        }
        return $languages;
    }

    /**
     * Explodes a string containing a comma separated list of language codes into an associative array
     * You can pass the config object $this->config->item('languages') as a parameter
     * @param string $languages_list comma separated list of language codes
     * @return array associative array (language code => language native name)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function nativelanguages($languages_list) {
        $languages = array();
        $lang_codes = explode(",", $languages_list);
        foreach($lang_codes as $lang_code) {
            $languages[$lang_code] =  $this->code2nativelanguage($lang_code) ;
        }
        return $languages;
    }

    /**
     * Convert a two characters language code to the english language name
     * @param string $code ISO 639-1 language code
     * @return string english language name
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function code2language($code) {
        switch (strtolower($code)) {
            //case 'ab' : return 'abkhaz'; break;
            //case 'aa' : return 'afar'; break;
            //case 'af' : return 'afrikaans'; break;
            //case 'ak' : return 'akan'; break;
            //case 'sq' : return 'albanian'; break;
            //case 'am' : return 'amharic'; break;
            case 'ar' : return 'arabic'; break;
            //case 'an' : return 'aragonese'; break;
            //case 'hy' : return 'armenian'; break;
            //case 'as' : return 'assamese'; break;
            //case 'av' : return 'avaric'; break;
            //case 'ae' : return 'avestan'; break;
            //case 'ay' : return 'aymara'; break;
            //case 'az' : return 'azerbaijani'; break;
            //case 'bm' : return 'bambara'; break;
            //case 'ba' : return 'bashkir'; break;
            //case 'eu' : return 'basque'; break;
            //case 'be' : return 'belarusian'; break;
            //case 'bn' : return 'bengali'; break;
            //case 'bh' : return 'bihari'; break;
            //case 'bi' : return 'bislama'; break;
            //case 'bs' : return 'bosnian'; break;
            //case 'br' : return 'breton'; break;
            //case 'bg' : return 'bulgarian'; break;
            //case 'my' : return 'burmese'; break;
            case 'ca' : return 'catalan'; break;
            //case 'ch' : return 'chamorro'; break;
            //case 'ce' : return 'chechen'; break;
            //case 'ny' : return 'chichewa'; break;
            case 'zh' : return 'chinese'; break;
            //case 'cv' : return 'chuvash'; break;
            //case 'kw' : return 'cornish'; break;
            //case 'co' : return 'corsican'; break;
            //case 'cr' : return 'cree'; break;
            //case 'hr' : return 'croatian'; break;
            case 'cs' : return 'czech'; break;
            //case 'da' : return 'danish'; break;
            //case 'dv' : return 'divehi'; break;
            case 'nl' : return 'dutch'; break;
            case 'en' : return 'english'; break;
            case 'en-gb' : return 'english_gb'; break;
            case 'en-GB' : return 'english_gb'; break;
            //case 'eo' : return 'esperanto'; break;
            //case 'et' : return 'estonian'; break;
            //case 'ee' : return 'ewe'; break;
            //case 'fo' : return 'faroese'; break;
            //case 'fj' : return 'fijian'; break;
            //case 'fi' : return 'finnish'; break;
            case 'fr' : return 'french'; break;
            //case 'ff' : return 'fula'; break;
            //case 'gl' : return 'galician'; break;
            //case 'ka' : return 'georgian'; break;
            case 'de' : return 'german'; break;
            case 'el' : return 'greek'; break;
            //case 'gn' : return 'guaraní'; break;
//            case 'gu' : return 'gujarati'; break;
//            case 'ht' : return 'haitian'; break;
//            case 'ha' : return 'hausa'; break;
//            case 'he' : return 'hebrew'; break;
//            case 'hz' : return 'herero'; break;
            case 'hi' : return 'hindi'; break;
//            case 'ho' : return 'hiri motu'; break;
            case 'hu' : return 'hungarian'; break;
//            case 'ia' : return 'interlingua'; break;
//            case 'id' : return 'indonesian'; break;
//            case 'ie' : return 'interlingue'; break;
//            case 'ga' : return 'irish'; break;
//            case 'ig' : return 'igbo'; break;
//            case 'ik' : return 'inupiaq'; break;
//            case 'io' : return 'ido'; break;
//            case 'is' : return 'icelandic'; break;
            case 'it' : return 'italian'; break;
//            case 'iu' : return 'inuktitut'; break;
//            case 'ja' : return 'japanese'; break;
//            case 'jv' : return 'javanese'; break;
//            case 'kl' : return 'kalaallisut'; break;
//            case 'kn' : return 'kannada'; break;
//            case 'kr' : return 'kanuri'; break;
//            case 'ks' : return 'kashmiri'; break;
//            case 'kk' : return 'kazakh'; break;
            case 'km' : return 'khmer'; break;
//            case 'ki' : return 'kikuyu'; break;
//            case 'rw' : return 'kinyarwanda'; break;
//            case 'ky' : return 'kirghiz'; break;
//            case 'kv' : return 'komi'; break;
//            case 'kg' : return 'kongo'; break;
//            case 'ko' : return 'korean'; break;
//            case 'ku' : return 'kurdish'; break;
//            case 'kj' : return 'kwanyama'; break;
//            case 'la' : return 'latin'; break;
//            case 'lb' : return 'luxembourgish'; break;
//            case 'lg' : return 'luganda'; break;
//            case 'li' : return 'limburgish'; break;
//            case 'ln' : return 'lingala'; break;
//            case 'lo' : return 'lao'; break;
//            case 'lt' : return 'lithuanian'; break;
//            case 'lu' : return 'luba-katanga'; break;
//            case 'lv' : return 'latvian'; break;
//            case 'gv' : return 'manx'; break;
//            case 'mk' : return 'macedonian'; break;
//            case 'mg' : return 'malagasy'; break;
//            case 'ms' : return 'malay'; break;
//            case 'ml' : return 'malayalam'; break;
//            case 'mt' : return 'maltese'; break;
//            case 'mi' : return 'māori'; break;
//            case 'mr' : return 'marathi'; break;
//            case 'mh' : return 'marshallese'; break;
//            case 'mn' : return 'mongolian'; break;
//            case 'na' : return 'nauru'; break;
//            case 'nv' : return 'navajo'; break;
//            case 'nb' : return 'norwegian bokmål'; break;
//            case 'nd' : return 'north ndebele'; break;
//            case 'ne' : return 'nepali'; break;
//            case 'ng' : return 'ndonga'; break;
//            case 'nn' : return 'norwegian nynorsk'; break;
//            case 'no' : return 'norwegian'; break;
//            case 'ii' : return 'nuosu'; break;
//            case 'nr' : return 'south ndebele'; break;
//            case 'oc' : return 'occitan'; break;
//            case 'oj' : return 'ojibwe'; break;
//            case 'cu' : return 'old church slavonic'; break;
//            case 'om' : return 'oromo'; break;
//            case 'or' : return 'oriya'; break;
//            case 'os' : return 'ossetian'; break;
//            case 'pa' : return 'panjabi'; break;
//            case 'pi' : return 'pāli'; break;
            case 'fa' : return 'persian'; break;
            case 'pl' : return 'polish'; break;
//            case 'ps' : return 'pashto'; break;
            case 'pt' : return 'portuguese'; break;
//            case 'qu' : return 'quechua'; break;
//            case 'rm' : return 'romansh'; break;
//            case 'rn' : return 'kirundi'; break;
            case 'ro' : return 'romanian'; break;
            case 'ru' : return 'russian'; break;
//            case 'sa' : return 'sanskrit'; break;
//            case 'sc' : return 'sardinian'; break;
//            case 'sd' : return 'sindhi'; break;
//            case 'se' : return 'northern sami'; break;
//            case 'sm' : return 'samoan'; break;
//            case 'sg' : return 'sango'; break;
//            case 'sr' : return 'serbian'; break;
//            case 'gd' : return 'scottish gaelic'; break;
//            case 'sn' : return 'shona'; break;
//            case 'si' : return 'sinhala'; break;
            case 'sk' : return 'slovak'; break;
//            case 'sl' : return 'slovene'; break;
//            case 'so' : return 'somali'; break;
//            case 'st' : return 'southern sotho'; break;
            case 'es' : return 'spanish'; break;
//            case 'su' : return 'sundanese'; break;
//            case 'sw' : return 'swahili'; break;
//            case 'ss' : return 'swati'; break;
//            case 'sv' : return 'swedish'; break;
//            case 'ta' : return 'tamil'; break;
//            case 'te' : return 'telugu'; break;
//            case 'tg' : return 'tajik'; break;
//            case 'th' : return 'thai'; break;
//            case 'ti' : return 'tigrinya'; break;
//            case 'bo' : return 'tibetan standard'; break;
//            case 'tk' : return 'turkmen'; break;
//            case 'tl' : return 'tagalog'; break;
//            case 'tn' : return 'tswana'; break;
//            case 'to' : return 'tonga'; break;
            case 'tr' : return 'turkish'; break;
//            case 'ts' : return 'tsonga'; break;
//            case 'tt' : return 'tatar'; break;
//            case 'tw' : return 'twi'; break;
//            case 'ty' : return 'tahitian'; break;
//            case 'ug' : return 'uighur'; break;
            case 'uk' : return 'ukrainian'; break;
//            case 'ur' : return 'urdu'; break;
//            case 'uz' : return 'uzbek'; break;
//            case 've' : return 'venda'; break;
            case 'vi' : return 'vietnamese'; break;
//            case 'vo' : return 'volapük'; break;
//            case 'wa' : return 'walloon'; break;
//            case 'cy' : return 'welsh'; break;
//            case 'wo' : return 'wolof'; break;
//            case 'fy' : return 'western frisian'; break;
//            case 'xh' : return 'xhosa'; break;
//            case 'yi' : return 'yiddish'; break;
//            case 'yo' : return 'yoruba'; break;
//            case 'za' : return 'zhuang'; break;
            default: return 'english'; break;
        }
    }

    /**
     * Returns the ISO 639-1 language code of an english language name
     * @param string $code english language name
     * @return string ISO 639-1 language code
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function language2code($language) {
        switch (strtolower($language)) {
//            case 'abkhaz' : return 'ab'; break;
//            case 'afar' : return 'aa'; break;
//            case 'afrikaans' : return 'af'; break;
//            case 'akan' : return 'ak'; break;
//            case 'albanian' : return 'sq'; break;
//            case 'amharic' : return 'am'; break;
            case 'arabic' : return 'ar'; break;
//            case 'aragonese' : return 'an'; break;
//            case 'armenian' : return 'hy'; break;
//            case 'assamese' : return 'as'; break;
//            case 'avaric' : return 'av'; break;
//            case 'avestan' : return 'ae'; break;
//            case 'aymara' : return 'ay'; break;
//            case 'azerbaijani' : return 'az'; break;
//            case 'bambara' : return 'bm'; break;
//            case 'bashkir' : return 'ba'; break;
//            case 'basque' : return 'eu'; break;
//            case 'belarusian' : return 'be'; break;
//            case 'bengali' : return 'bn'; break;
//            case 'bihari' : return 'bh'; break;
//            case 'bislama' : return 'bi'; break;
//            case 'bosnian' : return 'bs'; break;
//            case 'breton' : return 'br'; break;
//            case 'bulgarian' : return 'bg'; break;
//            case 'burmese' : return 'my'; break;
            case 'catalan' : return 'ca'; break;
//            case 'chamorro' : return 'ch'; break;
//            case 'chechen' : return 'ce'; break;
//            case 'chichewa' : return 'ny'; break;
            case 'chinese' : return 'zh'; break;
//            case 'chuvash' : return 'cv'; break;
//            case 'cornish' : return 'kw'; break;
//            case 'corsican' : return 'co'; break;
//            case 'cree' : return 'cr'; break;
//            case 'croatian' : return 'hr'; break;
            case 'czech' : return 'cs'; break;
//            case 'danish' : return 'da'; break;
//            case 'divehi' : return 'dv'; break;
            case 'dutch' : return 'nl'; break;
            case 'english' : return 'en'; break;
            case 'english_gb' : return 'en-GB'; break;
//            case 'esperanto' : return 'eo'; break;
//            case 'estonian' : return 'et'; break;
//            case 'ewe' : return 'ee'; break;
//            case 'faroese' : return 'fo'; break;
//            case 'fijian' : return 'fj'; break;
//            case 'finnish' : return 'fi'; break;
            case 'french' : return 'fr'; break;
//            case 'fula' : return 'ff'; break;
//            case 'galician' : return 'gl'; break;
//            case 'georgian' : return 'ka'; break;
            case 'german' : return 'de'; break;
            case 'greek' : return 'el'; break;
//            case 'guaraní' : return 'gn'; break;
//            case 'gujarati' : return 'gu'; break;
//            case 'haitian' : return 'ht'; break;
//            case 'hausa' : return 'ha'; break;
//            case 'hebrew' : return 'he'; break;
//            case 'herero' : return 'hz'; break;
//            case 'hindi' : return 'hi'; break;
//            case 'hiri motu' : return 'ho'; break;
            case 'hungarian' : return 'hu'; break;
//            case 'interlingua' : return 'ia'; break;
//            case 'indonesian' : return 'id'; break;
//            case 'interlingue' : return 'ie'; break;
//            case 'irish' : return 'ga'; break;
//            case 'igbo' : return 'ig'; break;
//            case 'inupiaq' : return 'ik'; break;
//            case 'ido' : return 'io'; break;
//            case 'icelandic' : return 'is'; break;
            case 'italian' : return 'it'; break;
//            case 'inuktitut' : return 'iu'; break;
//            case 'japanese' : return 'ja'; break;
//            case 'javanese' : return 'jv'; break;
//            case 'kalaallisut' : return 'kl'; break;
//            case 'kannada' : return 'kn'; break;
//            case 'kanuri' : return 'kr'; break;
//            case 'kashmiri' : return 'ks'; break;
//            case 'kazakh' : return 'kk'; break;
            case 'khmer' : return 'km'; break;
//            case 'kikuyu' : return 'ki'; break;
//            case 'kinyarwanda' : return 'rw'; break;
//            case 'kirghiz' : return 'ky'; break;
//            case 'komi' : return 'kv'; break;
//            case 'kongo' : return 'kg'; break;
//            case 'korean' : return 'ko'; break;
//            case 'kurdish' : return 'ku'; break;
//            case 'kwanyama' : return 'kj'; break;
//            case 'latin' : return 'la'; break;
//            case 'luxembourgish' : return 'lb'; break;
//            case 'luganda' : return 'lg'; break;
//            case 'limburgish' : return 'li'; break;
//            case 'lingala' : return 'ln'; break;
//            case 'lao' : return 'lo'; break;
//            case 'lithuanian' : return 'lt'; break;
//            case 'luba-katanga' : return 'lu'; break;
//            case 'latvian' : return 'lv'; break;
//            case 'manx' : return 'gv'; break;
//            case 'macedonian' : return 'mk'; break;
//            case 'malagasy' : return 'mg'; break;
//            case 'malay' : return 'ms'; break;
//            case 'malayalam' : return 'ml'; break;
//            case 'maltese' : return 'mt'; break;
//            case 'māori' : return 'mi'; break;
//            case 'marathi' : return 'mr'; break;
//            case 'marshallese' : return 'mh'; break;
//            case 'mongolian' : return 'mn'; break;
//            case 'nauru' : return 'na'; break;
//            case 'navajo' : return 'nv'; break;
//            case 'norwegian bokmål' : return 'nb'; break;
//            case 'north ndebele' : return 'nd'; break;
//            case 'nepali' : return 'ne'; break;
//            case 'ndonga' : return 'ng'; break;
//            case 'norwegian nynorsk' : return 'nn'; break;
//            case 'norwegian' : return 'no'; break;
//            case 'nuosu' : return 'ii'; break;
//            case 'south ndebele' : return 'nr'; break;
//            case 'occitan' : return 'oc'; break;
//            case 'ojibwe' : return 'oj'; break;
//            case 'old church slavonic' : return 'cu'; break;
//            case 'oromo' : return 'om'; break;
//            case 'oriya' : return 'or'; break;
//            case 'ossetian' : return 'os'; break;
//            case 'panjabi' : return 'pa'; break;
//            case 'pāli' : return 'pi'; break;
            case 'persian' : return 'fa'; break;
            case 'polish' : return 'pl'; break;
//            case 'pashto' : return 'ps'; break;
            case 'portuguese' : return 'pt'; break;
//            case 'quechua' : return 'qu'; break;
//            case 'romansh' : return 'rm'; break;
//            case 'kirundi' : return 'rn'; break;
            case 'romanian' : return 'ro'; break;
            case 'russian' : return 'ru'; break;
//            case 'sanskrit' : return 'sa'; break;
//            case 'sardinian' : return 'sc'; break;
//            case 'sindhi' : return 'sd'; break;
//            case 'northern sami' : return 'se'; break;
//            case 'samoan' : return 'sm'; break;
//            case 'sango' : return 'sg'; break;
//            case 'serbian' : return 'sr'; break;
//            case 'scottish gaelic' : return 'gd'; break;
//            case 'shona' : return 'sn'; break;
//            case 'sinhala' : return 'si'; break;
            case 'slovak' : return 'sk'; break;
//            case 'slovene' : return 'sl'; break;
//            case 'somali' : return 'so'; break;
//            case 'southern sotho' : return 'st'; break;
            case 'spanish' : return 'es'; break;
//            case 'sundanese' : return 'su'; break;
//            case 'swahili' : return 'sw'; break;
//            case 'swati' : return 'ss'; break;
//            case 'swedish' : return 'sv'; break;
//            case 'tamil' : return 'ta'; break;
//            case 'telugu' : return 'te'; break;
//            case 'tajik' : return 'tg'; break;
//            case 'thai' : return 'th'; break;
//            case 'tigrinya' : return 'ti'; break;
//            case 'tibetan standard' : return 'bo'; break;
//            case 'turkmen' : return 'tk'; break;
//            case 'tagalog' : return 'tl'; break;
//            case 'tswana' : return 'tn'; break;
//            case 'tonga' : return 'to'; break;
            case 'turkish' : return 'tr'; break;
//            case 'tsonga' : return 'ts'; break;
//            case 'tatar' : return 'tt'; break;
//            case 'twi' : return 'tw'; break;
//            case 'tahitian' : return 'ty'; break;
//            case 'uighur' : return 'ug'; break;
            case 'ukrainian' : return 'uk'; break;
//            case 'urdu' : return 'ur'; break;
//            case 'uzbek' : return 'uz'; break;
//            case 'venda' : return 've'; break;
            case 'vietnamese' : return 'vi'; break;
//            case 'volapük' : return 'vo'; break;
//            case 'walloon' : return 'wa'; break;
//            case 'welsh' : return 'cy'; break;
//            case 'wolof' : return 'wo'; break;
//            case 'western frisian' : return 'fy'; break;
//            case 'xhosa' : return 'xh'; break;
//            case 'yiddish' : return 'yi'; break;
//            case 'yoruba' : return 'yo'; break;
//            case 'zhuang' : return 'za'; break;
            default: return 'en'; break;
        }
    }

    /**
     * Convert a two characters language code to the language native name
     * @param string $code ISO 639-1 language code
     * @return string language native name
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function code2nativelanguage($code) {
        switch (strtolower($code)) {
//            case 'ab' : return 'аҧсуа'; break;
//            case 'aa' : return 'Afaraf'; break;
//            case 'af' : return 'Afrikaans'; break;
//            case 'ak' : return 'Akan'; break;
//            case 'sq' : return 'Shqip'; break;
//            case 'am' : return 'አማርኛ'; break;
            case 'ar' : return 'العربية'; break;
//            case 'an' : return 'Aragonés'; break;
//            case 'hy' : return 'Հայերեն'; break;
//            case 'as' : return 'অসমীয়া'; break;
//            case 'av' : return 'авар мацӀ'; break;
//            case 'ae' : return 'avesta'; break;
//            case 'ay' : return 'aymar aru'; break;
//            case 'az' : return 'azərbaycan dili'; break;
//            case 'bm' : return 'bamanankan'; break;
//            case 'ba' : return 'башҡорт теле'; break;
//            case 'eu' : return 'euskara'; break;
//            case 'be' : return 'Беларуская'; break;
//            case 'bn' : return 'বাংলা'; break;
//            case 'bh' : return 'भोजपुरी'; break;
//            case 'bi' : return 'Bislama'; break;
//            case 'bs' : return 'bosanski jezik'; break;
//            case 'br' : return 'brezhoneg'; break;
//            case 'bg' : return 'български език'; break;
//            case 'my' : return 'ဗမာစာ'; break;
            case 'ca' : return 'Català'; break;
//            case 'ch' : return 'Chamoru'; break;
//            case 'ce' : return 'нохчийн мотт'; break;
//            case 'ny' : return 'chiCheŵa'; break;
            case 'zh' : return '中文'; break;
//            case 'cv' : return 'чӑваш чӗлхи'; break;
//            case 'kw' : return 'Kernewek'; break;
//            case 'co' : return 'corsu'; break;
//            case 'cr' : return 'ᓀᐦᐃᔭᐍᐏᐣ'; break;
//            case 'hr' : return 'hrvatski'; break;
            case 'cs' : return 'Česky'; break;
//            case 'da' : return 'Dansk'; break;
//            case 'dv' : return 'ދިވެހި'; break;
            case 'nl' : return 'Nederlands'; break;
            case 'en' : return 'English'; break;
            case 'en-gb' : return 'English (UK)'; break;
            case 'en-GB' : return 'English (UK)'; break;
//            case 'eo' : return 'Esperanto'; break;
//            case 'et' : return 'eesti'; break;
//            case 'ee' : return 'Eʋegbe'; break;
//            case 'fo' : return 'føroyskt'; break;
//            case 'fj' : return 'vosa Vakaviti'; break;
//            case 'fi' : return 'suomi'; break;
            case 'fr' : return 'Français'; break;
//            case 'ff' : return 'Fulfulde'; break;
//            case 'gl' : return 'Galego'; break;
//            case 'ka' : return 'ქართული'; break;
            case 'de' : return 'Deutsch'; break;
            case 'el' : return 'Ελληνικά'; break;
//            case 'gn' : return 'Avañeẽ'; break;
//            case 'gu' : return 'ગુજરાતી'; break;
//            case 'ht' : return 'Kreyòl ayisyen'; break;
//            case 'ha' : return 'Hausa'; break;
//            case 'he' : return 'עברית'; break;
//            case 'hz' : return 'Otjiherero'; break;
//            case 'hi' : return 'हिन्दी'; break;
//            case 'ho' : return 'Hiri Motu'; break;
            case 'hu' : return 'Magyar'; break;
//            case 'ia' : return 'Interlingua'; break;
//            case 'id' : return 'Bahasa Indonesia'; break;
//            case 'ie' : return 'Interlingue'; break;
//            case 'ga' : return 'Gaeilge'; break;
//            case 'ig' : return 'Asụsụ Igbo'; break;
//            case 'ik' : return 'Iñupiaq'; break;
//            case 'io' : return 'Ido'; break;
//            case 'is' : return 'Íslenska'; break;
            case 'it' : return 'Italiano'; break;
//            case 'iu' : return 'ᐃᓄᒃᑎᑐᑦ'; break;
//            case 'ja' : return '日本語'; break;
//            case 'jv' : return 'basa Jawa'; break;
//            case 'kl' : return 'nativeName'; break;
//            case 'kn' : return 'ಕನ್ನಡ'; break;
//            case 'kr' : return 'Kanuri'; break;
//            case 'ks' : return 'कश्मीरी'; break;
//            case 'kk' : return 'Қазақ тілі'; break;
            case 'km' : return 'ភាសាខ្មែរ'; break;
//            case 'ki' : return 'nativeName'; break;
//            case 'rw' : return 'Ikinyarwanda'; break;
//            case 'ky' : return 'nativeName'; break;
//            case 'kv' : return 'коми кыв'; break;
//            case 'kg' : return 'KiKongo'; break;
//            case 'ko' : return '한국어'; break;
//            case 'ku' : return 'Kurdî'; break;
//            case 'kj' : return 'nativeName'; break;
//            case 'la' : return 'latine'; break;
//            case 'lb' : return 'nativeName'; break;
//            case 'lg' : return 'Luganda'; break;
//            case 'li' : return ' Limburger'; break;
//            case 'ln' : return 'Lingála'; break;
//            case 'lo' : return 'ພາສາລາວ'; break;
//            case 'lt' : return 'lietuvių kalba'; break;
//            case 'lu' : return ''; break;
//            case 'lv' : return 'latviešu valoda'; break;
//            case 'gv' : return 'Gaelg'; break;
//            case 'mk' : return 'македонски јазик'; break;
//            case 'mg' : return 'Malagasy fiteny'; break;
//            case 'ms' : return 'bahasa Melayu'; break;
//            case 'ml' : return 'മലയാളം'; break;
//            case 'mt' : return 'Malti'; break;
//            case 'mi' : return 'te reo Māori'; break;
//            case 'mr' : return 'मराठी'; break;
//            case 'mh' : return 'Kajin M̧ajeļ'; break;
//            case 'mn' : return 'монгол'; break;
//            case 'na' : return 'Ekakairũ Naoero'; break;
//            case 'nv' : return 'nativeName'; break;
//            case 'nb' : return 'Norsk bokmål'; break;
//            case 'nd' : return 'isiNdebele'; break;
//            case 'ne' : return 'नेपाली'; break;
//            case 'ng' : return 'Owambo'; break;
//            case 'nn' : return 'Norsk nynorsk'; break;
//            case 'no' : return 'Norsk'; break;
//            case 'ii' : return 'ꆈꌠ꒿ Nuosuhxop'; break;
//            case 'nr' : return 'isiNdebele'; break;
//            case 'oc' : return 'Occitan'; break;
//            case 'oj' : return 'nativeName'; break;
//            case 'cu' : return ' Church Slavonic'; break;
//            case 'om' : return 'Afaan Oromoo'; break;
//            case 'or' : return 'ଓଡ଼ିଆ'; break;
//            case 'os' : return 'nativeName'; break;
//            case 'pa' : return 'nativeName'; break;
//            case 'pi' : return 'पाऴि'; break;
            case 'fa' : return 'فارسی'; break;
            case 'pl' : return 'Polski'; break;
//            case 'ps' : return 'nativeName'; break;
            case 'pt' : return 'Português'; break;
//            case 'qu' : return 'Runa Simi'; break;
//            case 'rm' : return 'rumantsch grischun'; break;
//            case 'rn' : return 'kiRundi'; break;
            case 'ro' : return ' Moldovan'; break;
            case 'ru' : return 'Pусский язык'; break;
//            case 'sa' : return 'संस्कृतम्'; break;
//            case 'sc' : return 'sardu'; break;
//            case 'sd' : return 'सिन्धी'; break;
//            case 'se' : return 'Davvisámegiella'; break;
//            case 'sm' : return 'Gagana faa Samoa'; break;
//            case 'sg' : return 'yângâ tî sängö'; break;
//            case 'sr' : return 'српски језик'; break;
//            case 'gd' : return 'Gàidhlig'; break;
//            case 'sn' : return 'chiShona'; break;
//            case 'si' : return 'nativeName'; break;
            case 'sk' : return 'slovenčina'; break;
//            case 'sl' : return 'slovenščina'; break;
//            case 'so' : return 'Soomaaliga'; break;
//            case 'st' : return 'Sesotho'; break;
            case 'es' : return 'Español'; break;
//            case 'su' : return 'Basa Sunda'; break;
//            case 'sw' : return 'Kiswahili'; break;
//            case 'ss' : return 'SiSwati'; break;
//            case 'sv' : return 'svenska'; break;
//            case 'ta' : return 'தமிழ்'; break;
//            case 'te' : return 'తెలుగు'; break;
//            case 'tg' : return 'тоҷикӣ'; break;
//            case 'th' : return 'ไทย'; break;
//            case 'ti' : return 'ትግርኛ'; break;
//            case 'bo' : return ' Central'; break;
//            case 'tk' : return 'Türkmen'; break;
//            case 'tl' : return 'Wikang Tagalog'; break;
//            case 'tn' : return 'Setswana'; break;
//            case 'to' : return 'faka Tonga'; break;
            case 'tr' : return 'Türkçe'; break;
//            case 'ts' : return 'Xitsonga'; break;
//            case 'tt' : return 'татарча'; break;
//            case 'tw' : return 'Twi'; break;
//            case 'ty' : return 'Reo Tahiti'; break;
//            case 'ug' : return 'nativeName'; break;
            case 'uk' : return 'українська'; break;
//            case 'ur' : return 'اردو'; break;
//            case 'uz' : return 'zbek'; break;
//            case 've' : return 'Tshivenḓa'; break;
            case 'vi' : return 'Tiếng Việt'; break;
//            case 'vo' : return 'Volapük'; break;
//            case 'wa' : return 'Walon'; break;
//            case 'cy' : return 'Cymraeg'; break;
//            case 'wo' : return 'Wollof'; break;
//            case 'fy' : return 'Frysk'; break;
//            case 'xh' : return 'isiXhosa'; break;
//            case 'yi' : return 'ייִדיש'; break;
//            case 'yo' : return 'Yorùbá'; break;
//            case 'za' : return 'nativeName'; break;
            default: return 'English'; break;
        }
    }

    /**
     * Returns the ISO 639-1 language code of a language native name
     * @param string language native name
     * @return string $code ISO 639-1 language code
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function nativelanguage2code($language) {
        switch (strtolower($language)) {
//            case 'аҧсуа' : return 'ab'; break;
//            case 'Afaraf' : return 'aa'; break;
//            case 'Afrikaans' : return 'af'; break;
//            case 'Akan' : return 'ak'; break;
//            case 'Shqip' : return 'sq'; break;
//            case 'አማርኛ' : return 'am'; break;
            case 'العربية' : return 'ar'; break;
//            case 'Aragonés' : return 'an'; break;
//            case 'Հայերեն' : return 'hy'; break;
//            case 'অসমীয়া' : return 'as'; break;
//            case 'авар мацӀ' : return 'av'; break;
//            case 'avesta' : return 'ae'; break;
//            case 'aymar aru' : return 'ay'; break;
//            case 'azərbaycan dili' : return 'az'; break;
//            case 'bamanankan' : return 'bm'; break;
//            case 'башҡорт теле' : return 'ba'; break;
//            case 'euskara' : return 'eu'; break;
//            case 'Беларуская' : return 'be'; break;
//            case 'বাংলা' : return 'bn'; break;
//            case 'भोजपुरी' : return 'bh'; break;
//            case 'Bislama' : return 'bi'; break;
//            case 'bosanski jezik' : return 'bs'; break;
//            case 'brezhoneg' : return 'br'; break;
//            case 'български език' : return 'bg'; break;
//            case 'ဗမာစာ' : return 'my'; break;
            case 'Català' : return 'ca'; break;
//            case 'Chamoru' : return 'ch'; break;
//            case 'нохчийн мотт' : return 'ce'; break;
//            case 'chiCheŵa' : return 'ny'; break;
            case '中文' : return 'zh'; break;
//            case 'чӑваш чӗлхи' : return 'cv'; break;
//            case 'Kernewek' : return 'kw'; break;
//            case 'corsu' : return 'co'; break;
//            case 'ᓀᐦᐃᔭᐍᐏᐣ' : return 'cr'; break;
//            case 'hrvatski' : return 'hr'; break;
            case 'Česky' : return 'cs'; break;
//            case 'dansk' : return 'da'; break;
//            case 'ދިވެހި' : return 'dv'; break;
            case 'Nederlands' : return 'nl'; break;
            case 'English' : return 'en'; break;
            case 'English (UK)' : return 'en-GB'; break;
//            case 'Esperanto' : return 'eo'; break;
//            case 'eesti' : return 'et'; break;
//            case 'Eʋegbe' : return 'ee'; break;
//            case 'føroyskt' : return 'fo'; break;
//            case 'vosa Vakaviti' : return 'fj'; break;
//            case 'suomi' : return 'fi'; break;
            case 'français' : return 'fr'; break;
//            case 'Fulfulde' : return 'ff'; break;
//            case 'Galego' : return 'gl'; break;
//            case 'ქართული' : return 'ka'; break;
            case 'Deutsch' : return 'de'; break;
            case 'Ελληνικά' : return 'el'; break;
//            case 'Avañeẽ' : return 'gn'; break;
//            case 'ગુજરાતી' : return 'gu'; break;
//            case 'Kreyòl ayisyen' : return 'ht'; break;
//            case 'Hausa' : return 'ha'; break;
//            case 'עברית' : return 'he'; break;
//            case 'Otjiherero' : return 'hz'; break;
//            case 'हिन्दी' : return 'hi'; break;
//            case 'Hiri Motu' : return 'ho'; break;
            case 'Magyar' : return 'hu'; break;
//            case 'Interlingua' : return 'ia'; break;
//            case 'Bahasa Indonesia' : return 'id'; break;
//            case 'Interlingue' : return 'ie'; break;
//            case 'Gaeilge' : return 'ga'; break;
//            case 'Asụsụ Igbo' : return 'ig'; break;
//            case 'Iñupiaq' : return 'ik'; break;
//            case 'Ido' : return 'io'; break;
//            case 'Íslenska' : return 'is'; break;
            case 'Italiano' : return 'it'; break;
//            case 'ᐃᓄᒃᑎᑐᑦ' : return 'iu'; break;
//            case '日本語' : return 'ja'; break;
//            case 'basa Jawa' : return 'jv'; break;
//            case 'nativeName' : return 'kl'; break;
//            case 'ಕನ್ನಡ' : return 'kn'; break;
//            case 'Kanuri' : return 'kr'; break;
//            case 'कश्मीरी' : return 'ks'; break;
//            case 'Қазақ тілі' : return 'kk'; break;
            case 'ភាសាខ្មែរ' : return 'km'; break;
//            case 'nativeName' : return 'ki'; break;
//            case 'Ikinyarwanda' : return 'rw'; break;
//            case 'nativeName' : return 'ky'; break;
//            case 'коми кыв' : return 'kv'; break;
//            case 'KiKongo' : return 'kg'; break;
//            case '한국어' : return 'ko'; break;
//            case 'Kurdî' : return 'ku'; break;
//            case 'nativeName' : return 'kj'; break;
//            case 'latine' : return 'la'; break;
//            case 'nativeName' : return 'lb'; break;
//            case 'Luganda' : return 'lg'; break;
//            case ' Limburger' : return 'li'; break;
//            case 'Lingála' : return 'ln'; break;
//            case 'ພາສາລາວ' : return 'lo'; break;
//            case 'lietuvių kalba' : return 'lt'; break;
//            case '' : return 'lu'; break;
//            case 'latviešu valoda' : return 'lv'; break;
//            case 'Gaelg' : return 'gv'; break;
//            case 'македонски јазик' : return 'mk'; break;
//            case 'Malagasy fiteny' : return 'mg'; break;
//            case 'bahasa Melayu' : return 'ms'; break;
//            case 'മലയാളം' : return 'ml'; break;
//            case 'Malti' : return 'mt'; break;
//            case 'te reo Māori' : return 'mi'; break;
//            case 'मराठी' : return 'mr'; break;
//            case 'Kajin M̧ajeļ' : return 'mh'; break;
//            case 'монгол' : return 'mn'; break;
//            case 'Ekakairũ Naoero' : return 'na'; break;
//            case 'nativeName' : return 'nv'; break;
//            case 'Norsk bokmål' : return 'nb'; break;
//            case 'isiNdebele' : return 'nd'; break;
//            case 'नेपाली' : return 'ne'; break;
//            case 'Owambo' : return 'ng'; break;
//            case 'Norsk nynorsk' : return 'nn'; break;
//            case 'Norsk' : return 'no'; break;
//            case 'ꆈꌠ꒿ Nuosuhxop' : return 'ii'; break;
//            case 'isiNdebele' : return 'nr'; break;
//            case 'Occitan' : return 'oc'; break;
//            case 'nativeName' : return 'oj'; break;
//            case ' Church Slavonic' : return 'cu'; break;
//            case 'Afaan Oromoo' : return 'om'; break;
//            case 'ଓଡ଼ିଆ' : return 'or'; break;
//            case 'nativeName' : return 'os'; break;
//            case 'nativeName' : return 'pa'; break;
//            case 'पाऴि' : return 'pi'; break;
//            case 'فارسی' : return 'fa'; break;
            case 'polski' : return 'pl'; break;
//            case 'nativeName' : return 'ps'; break;
            case 'Português' : return 'pt'; break;
//            case 'Runa Simi' : return 'qu'; break;
//            case 'rumantsch grischun' : return 'rm'; break;
//            case 'kiRundi' : return 'rn'; break;
            case ' Moldovan' : return 'ro'; break;
            case 'Pусский язык' : return 'ru'; break;
//            case 'संस्कृतम्' : return 'sa'; break;
//            case 'sardu' : return 'sc'; break;
//            case 'सिन्धी' : return 'sd'; break;
//            case 'Davvisámegiella' : return 'se'; break;
//            case 'gagana faa Samoa' : return 'sm'; break;
//            case 'yângâ tî sängö' : return 'sg'; break;
//            case 'српски језик' : return 'sr'; break;
//            case 'Gàidhlig' : return 'gd'; break;
//            case 'chiShona' : return 'sn'; break;
//            case 'nativeName' : return 'si'; break;
            case 'slovenčina' : return 'sk'; break;
//            case 'slovenščina' : return 'sl'; break;
//            case 'Soomaaliga' : return 'so'; break;
//            case 'Sesotho' : return 'st'; break;
            case 'español' : return 'es'; break;
//            case 'Basa Sunda' : return 'su'; break;
//            case 'Kiswahili' : return 'sw'; break;
//            case 'SiSwati' : return 'ss'; break;
//            case 'svenska' : return 'sv'; break;
//            case 'தமிழ்' : return 'ta'; break;
//            case 'తెలుగు' : return 'te'; break;
//            case 'тоҷикӣ' : return 'tg'; break;
//            case 'ไทย' : return 'th'; break;
//            case 'ትግርኛ' : return 'ti'; break;
//            case ' Central' : return 'bo'; break;
//            case 'Türkmen' : return 'tk'; break;
//            case 'Wikang Tagalog' : return 'tl'; break;
//            case 'Setswana' : return 'tn'; break;
//            case 'faka Tonga' : return 'to'; break;
            case 'Türkçe' : return 'tr'; break;
//            case 'Xitsonga' : return 'ts'; break;
//            case 'татарча' : return 'tt'; break;
//            case 'Twi' : return 'tw'; break;
//            case 'Reo Tahiti' : return 'ty'; break;
//            case 'nativeName' : return 'ug'; break;
            case 'українська' : return 'uk'; break;
//            case 'اردو' : return 'ur'; break;
//            case 'zbek' : return 'uz'; break;
//            case 'Tshivenḓa' : return 've'; break;
            case 'Tiếng Việt' : return 'vi'; break;
//            case 'Volapük' : return 'vo'; break;
//            case 'Walon' : return 'wa'; break;
//            case 'Cymraeg' : return 'cy'; break;
//            case 'Wollof' : return 'wo'; break;
//            case 'Frysk' : return 'fy'; break;
//            case 'isiXhosa' : return 'xh'; break;
//            case 'ייִדיש' : return 'yi'; break;
//            case 'Yorùbá' : return 'yo'; break;
//            case 'nativeName' : return 'za'; break;
            default: return 'en'; break;
        }
    }
}
