<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Url Helper
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
     * Return GET
     *
     * @param  string $var
     * @return string $_GET[$var]
     */
if ( ! function_exists('get')) {
    function get($var){
        if (isGet($var)) {
            return $_GET[$var];
        }
    }
}

    /**
     * Check is GET variable exists
     *
     * @param  string $var
     * @return boolean $_GET[$var]
     */
if ( ! function_exists('isGet')) {
    function isGet($var){
        if (isset($_GET[$var])) return true; else return false;
    }
}

    /**
     * Return POST
     *
     * @param  string $var
     * @return string $_POST[$var]
     */
if ( ! function_exists('post')) {
    function post($var){
        return $_POST[$var];
    }
}

    /**
     * Check is POST variable exists
     *
     * @param  string $var
     * @return boolean $_POST[$var]
     */
if ( ! function_exists('isPost')) {
    function isPost($var){
        if (isset($_POST[$var])) return true; else return false;
    }
}


    /**
     * Redirects the browser to a page specified by the $url argument  
     *
     * @param string $url The URL
     */
if ( ! function_exists('redirect')) {
    function redirect($url){
        if (headers_sent()) {
            echo "<script>document.location.href='".$url."';</script>\n";
        } else {
            header("Location: $url");
        }
        exit(0);
    }
}

    /**
     * Set status header
     *
     * @param integer $status Header status code
     */
if ( ! function_exists('statusHeader')) {
    function statusHeader($status) {
        switch ($status) {
            case 301:
                header("HTTP/1.1 301 Moved Permanently");
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.1 404 Not Found");
                break;
        }
    }
}

    /**
     * Returns the current url
     *
     * @return string
     */
if ( ! function_exists('selfUrl')) {
    function selfUrl() {
        return htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES);
    }
}

    /**
     * Get the current Page URL
     *
     * @return string
     */
if ( ! function_exists('curUrl')) {
    function curUrl() {
        $page_url = "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $page_url;
    }
}

    /**
     * Takes a long url and uses the TinyURL API to return a shortened version.
     *
     * @param  string Long url
     * @return string
     */
if ( ! function_exists('tinyUrl')) {
    function tinyUrl($url) {
        return file_get_contents('http://tinyurl.com/api-create.php?url='.$url);
    }
}

    /**
     * Check is url exists
     *
     * @author Fabrizio
     * @see http://www.php.net/manual/en/function.file-exists.php#59986
     * @param string $url Url
     * @return boolean
     */
if ( ! function_exists('urlExists')) {
    function urlExists($url) {
        $a_url = parse_url($url);
        if ( ! isset($a_url['port'])) $a_url['port'] = 80;
        $errno = 0;
        $errstr = '';
        $timeout = 30;
        if (isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
            $fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
            if ( ! $fid) return false;
            $page = isset($a_url['path'])  ?$a_url['path']:'';
            $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
            fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
            $head = fread($fid, 4096);
            fclose($fid);
            return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
        } else {
            return false;
        }
    }
}

    /**
     * Find url 
     *
     * @global string $site_url Site url
     * @param string $url URL - Uniform Resource Locator
     * @return string
     */
if ( ! function_exists('findUrl')) {
    function findUrl($url) {
        global $site_url;

        $pos = strpos($url, 'http://');
        if ($pos === false) {
            $url_output = $site_url.$url;
        } else {
            $url_output = $url;
        }

        return $url_output;
    }
}
    
    // 
    /**
     * Autodetect base URL if necessary 
     *
     * @param boolean $is_full - get full or relarive url
     * @return string URL - Uniform Resource Locator
     */
    function get_base_url($is_full = true){
        // Как глубоко DOCUMENT_ROOT
        $rdepth = count(explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['DOCUMENT_ROOT'])));
        // Как глубоко текущий файл относительно главного index'a.
        $fdepth = 2;
        // Физический путь
        $fpath  = array_slice(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)), $depth, -$fdepth);
        // URL - путь
        $upath  = explode('/', urldecode(trim(str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '?/')));
        $ais    = array_intersect($fpath, $upath);
        // echo '<pre>'; print_r($fpath); print_r($upath); print_r($ais); die();
        if($is_full) $base = ($_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://').$_SERVER["SERVER_NAME"].'/'; else $base = '';
        return $base.($ais ? implode('/', $ais).'/' : '');
    }  