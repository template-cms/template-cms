<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Security module
     *
     *	@package TemplateCMS
     *  @subpackage Engine
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
     * Encrypt password
     *
     * @param string $password Password to encrypt
     */
    function encryptPassword($password) {
       return md5(md5(trim($password).TEMPLATE_CMS_PASSWORD_SALT));
    }


    /**
     * Create safe name. Use to create safe username, filename, pagename.
     *
     * @param  string  $str        String
     * @param  string  $delimiter  String delimiter
     * @param  boolean $lowercase  String Lowercase
     * @return string
     */
    function safeName($str, $delimiter = '-', $lowercase = false) {
        
        // Redefine vars
        $str       = (string) $str;
        $delimiter = (string) $delimiter;
        $lowercase = (bool)   $lowercase;

        // Allow underscore, otherwise default to dash
        $delimiter = $delimiter === '_' ? '_' : '-';

        // Remove tags
        $str = filter_var($str, FILTER_SANITIZE_STRING);

        // Decode all entities to their simpler forms
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

        // Reserved characters (RFC 3986)
        $reserved_characters = array(
            '/', '?', ':', '@', '#', '[', ']',
            '!', '$', '&', '\'', '(', ')', '*',
            '+', ',', ';', '='
        );

        // Remove reserved characters
        $str = str_replace($reserved_characters, ' ', $str);

        // Set locale to en_US.UTF8
        setlocale(LC_ALL, 'en_US.UTF8');

        // Translit ua,ru => latin          
        $str = translitIt($str);

        // Convert string
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);

        // Remove characters
        $str = preg_replace("/[^a-zA-Z0-9.\/_|+ -]/", '', $str );
        $str = preg_replace("/[\/_|+ -]+/", $delimiter, $str );
        $str = trim($str, $delimiter);  

        // Lowercase
        if ($lowercase === true) $str = lowercase($str);            

        // Return safe name
        return $str;
     }


    /**
     * Create safe url. 
     *
     * @param string $url Url to sanitize
     * @return string
     */
    function sanitizeURL($url) {
        $url = trim($url);
        $url = rawurldecode($url);
        $url = str_replace(array('--','&quot;','!','@','#','$','%','^','*','(',')','+','{','}','|',':','"','<','>',
                                  '[',']','\\',';',"'",',','*','+','~','`','laquo','raquo',']>','&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;'),
                            array('-','-','','','','','','','','','','','','','','','','','','','','','','','','','','',''),
                            $url);
        $url = str_replace('--', '-', $url);
        $url = rtrim($url, "-");
        
        $url = str_replace('..', '', $url);
        $url = str_replace('//', '', $url);
        $url = preg_replace('/^\//', '', $url);
        $url = preg_replace('/^\./', '', $url);   

        return $url;
     }


    /**
     * Convert html to plain text
     *
     * @param string $str String
     * @return string
     */
    function toText($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'utf-8');        
    }


    /**
     * Convert plain text to html
     *
     * @param string $str String
     * @return string
     */
    function toHtml($str) {
        return html_entity_decode($str, ENT_QUOTES, 'utf-8');
    }


    /**
     * Convert html from $_POST to plain text. 
     * note: Use before save data from $_POST. 
     *       Dont use it with XMLDB API. XMLDB performs xssClean automatically.
     */
    function htmlPostText() {
        $_POST = array_map('xssClean', $_POST);
    }


    /**
     * Sanitize URL to prevent XSS - Cross-site scripting
     */
    function runSanitizeURL() {
        $_GET = array_map('sanitizeURL', $_GET);
    }


    /**
     * That prevents null characters between ascii characters. 
     *
     * @param string $str String
     */
    function removeInvisibleCharacters($str) {
        // Thanks to ci for this tip :)
        $non_displayables = array('/%0[0-8bcef]/','/%1[0-9a-f]/','/[\x00-\x08]/','/\x0b/','/\x0c/','/[\x0e-\x1f]/');
        
        do {
            $cleaned = $str;
            $str = preg_replace($non_displayables, '', $str);
        } while ($cleaned != $str);

        return $str;
    }


    /**
     * Sanitize data to prevent XSS - Cross-site scripting
     *
     * @param string $str String
     */
    function xssClean($str) {

        // Remove invisible characters
        $str = removeInvisibleCharacters($str);

        // Convert html to plain text
        $str = toText($str); 

        return $str;
    }