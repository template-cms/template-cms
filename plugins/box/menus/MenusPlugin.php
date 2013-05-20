<?php

    /**
     *	Menus plugin
     *
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 1.0
     *
     */


    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   '<a href="index.php?id=themes&sub_id=menus">Menus</a>|box',
                   '1.0',              
                   'Menus managment plugin',
                   'Awilum',          
                   'http://awilum.webdevart.ru/',
                   'menusAdmin');     


    // Get language file for this plugin
    getPluginLanguage('Menus', 'box');  


    // Add template hook
    addHook('menus_site','menusSite',array());
  

    // Include Admin
    getPluginAdmin('Menus', 'box');
    

    /**
     * Menus template function
     * @param string $file menu file
     * @param array $data uri data
     */
    function menusSite($file,$data) {

        $site_url = getSiteUrl(false);
        $defpage = getDefaultPage(false);

        // Path to menus file
        $menu_file_path = TEMPLATE_CMS_DATA_PATH.'menus/'.$file.'.xml';

        if (file_exists($menu_file_path)) {

            // Get XML db
            $xml_db = getXMLdb($menu_file_path);

            // Get records from menus database
            $records = selectXMLRecord($xml_db, "menu", 'all');

            // Get fields from array of menus records
            $menus_records = selectXMLfields($records, array('menu_name', 'menu_link', 'menu_target', 'menu_order'), 'menu_order', 'ASC');

            // Load menu template
            include 'templates/frontend/MenusTemplate.php';
        }
    }