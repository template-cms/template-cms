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
      
        
    // Preload main system variables for CORE/BOX PLUGINS       
    global $site_url,$system_language,$system_timezone,$defpage,$system_site_name,$system_site_slogan,$system_site_title,$system_site_description,$system_site_keywords,$current_theme;    
    $options_xml             = getXMLdb($admin_path.TEMPLATE_CMS_DATA_PATH.'system/options.xml');        
    
    if ($options_xml['xml_object'] !== false) {
        $site_url                = $options_xml['xml_object']->siteurl->value;
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