<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Text Helper
     *
     *	@package TemplateCMS
     *	@subpackage Helpers
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


    /**
     * Translit function ua,ru => latin
     *
     * @param string $str [ua,ru] string
     * @return string $str 
     */
if ( ! function_exists('translitIt')) {
    function translitIt($str) {

        $patern = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z",
            "И" => "I", "Й" => "Y", "К" => "K", "Л" => "L",
            "М" => "M", "Н" => "N", "О" => "O", "П" => "P",
            "Р" => "R", "С" => "S", "Т" => "T", "У" => "U",
            "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", 
            "Ь" => "", "Э" => "E", "Ю" => "YU", "Я" => "YA",
            "а" => "a", "б" => "b", "в" => "v", "г" => "g",
            "д" => "d", "е" => "e", "ж" => "j", "з" => "z", 
            "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o","п" => "p",
            "р" => "r", "с" => "s", "т" => "t", "у" => "u", 
            "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch",
            "ш" => "sh", "щ" => "sch", "ъ" => "y", "ї" => "i",
            "Ї" => "Yi", "є" => "ie", "Є" => "Ye", "ы" => "yi",
            "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", "ё" => "yo"
        );
        
        return strtr($str,$patern);
    }
}

    /**
     * Removes any leading and traling slashes from a string
     *
     * @param string $str String with slashes
     * @return string
     */
if ( ! function_exists('trimSlashes')) {
    function trimSlashes($str) {
        return trim($str, '/');
    }
}

    /**
     * Removes slashes contained in a string or in an array
     *
     * @param string $str String with slashes
     * @return string
     */
if ( ! function_exists('strpSlashes')) {
    function strpSlashes($str) {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = stripslashes($val);
            }
        } else {
            $str = stripslashes($str);
        }
        return $str;
    }
}

    /**
     * Removes single and double quotes from a string
     *
     * @param string $str String with single and double quotes
     * @return string
     */
if ( ! function_exists('stripQuotes')) {
    function stripQuotes($str) {
        return str_replace(array('"', "'"), '', $str);
    }
}

    /**
     * Converts single and double quotes to entities
     *
     * @param string $str String with single and double quotes
     * @return string
     */
if ( ! function_exists('quotesToEntities')) {
    function quotesToEntities($str) {
        return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $str);
    }
}

    /**
     * Generate unique string
     *
     * Example: string like 1b491ba93b2b291755ff9a591547a5ba
     * @return string
     */
if ( ! function_exists('getUniqueString')) {
    function getUniqueString() {
        return md5(uniqid(rand(), true));
    }
}

    /**
     * Cut string
     *
     * @param string $str input string
     * @param integer $length Length after cut
     * @param string $cut_msg Message after cut string
     * @return string
     */
if ( ! function_exists('cutString')) {
    function cutString($str,$length,$cut_msg=null) {
        if (isset($cut_msg)) {
            $msg = $cut_msg;
        } else {
            $msg = '...';
        }
        return substr($str, 0, (int)$length).$msg;
    }
}


    /**
     * Safe lowecase
     *
     * @param string $str String
     * @return string
     */
if ( ! function_exists('lowecase')) {
    function lowercase($text) {
        if (function_exists('mb_strtolower')) {
            $text = mb_strtolower($text);
        } else {
            $text = strtolower($text);
        }
        return $text;
    }
}