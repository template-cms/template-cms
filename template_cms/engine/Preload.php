<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *  Preload module
     *
     *  @package TemplateCMS
     *  @subpackage Engine
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 - 2012 Romanenko Sergey / Awilum
     *  @version $Id$
     *  @since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource
     */


    // Gets the current configuration setting of magic_quotes_gpc
    // and kill magic quotes
    if (get_magic_quotes_gpc()) {
        function stripslashes_gpc(&$value) { $value = stripslashes($value); }
        array_walk_recursive($_GET, 'stripslashes_gpc');
        array_walk_recursive($_POST, 'stripslashes_gpc');
        array_walk_recursive($_COOKIE, 'stripslashes_gpc');
        array_walk_recursive($_REQUEST, 'stripslashes_gpc');
    }
    
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
        $fpath  = array_slice(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)), $rdepth, -$fdepth);
        // URL - путь
        $upath  = explode('/', urldecode(trim(str_replace($_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']), '?/')));
        $ais    = array_intersect($fpath, $upath);
        // echo '<pre>'; print_r($fpath); print_r($upath); print_r($ais); die();
        if($is_full) $base = ($_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://').$_SERVER["SERVER_NAME"].'/'; else $base = '';
        return $base.($ais ? implode('/', $ais).'/' : '');
    } 
    
    // Preload main system variables for CORE/BOX PLUGINS       
    global $site_url,$system_language,$system_timezone,$defpage,$system_site_name,$system_site_slogan,$system_site_title,$system_site_description,$system_site_keywords,$current_theme;    
    $options_xml             = getXMLdb($admin_path.TEMPLATE_CMS_DATA_PATH.'system/options.xml');        
    
    if ($options_xml['xml_object'] !== false) {
        $site_url                = $options_xml['xml_object']->siteurl->value != '/' ? $options_xml['xml_object']->siteurl->value : get_base_url();
        $system_language         = $options_xml['xml_object']->language->value;
        $system_timezone         = $options_xml['xml_object']->timezone->value;        
        $defpage                 = $options_xml['xml_object']->defaultpage->value;   
        $system_site_name        = $options_xml['xml_object']->sitename->value;
        $system_site_description = $options_xml['xml_object']->description->value;
        $system_site_keywords    = $options_xml['xml_object']->keywords->value;
        $system_site_title       = $options_xml['xml_object']->title->value;
        $system_site_slogan      = $options_xml['xml_object']->slogan->value;        
        $current_theme           = $options_xml['xml_object']->theme_name->value;        
    }
    
    global $options_xml;
    
    // Set the default timezone used by all date/time functions
    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set($system_timezone);
    }