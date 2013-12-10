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
    function render_menu_list_item($menu, $data, $sub = false, $subsub = false){
        global $site_url;
        $pos = strpos($menu['menu_link'], 'http://');
        if ($pos === false) {
            $link = $site_url.$menu['menu_link'];
        } else {
            $link = $menu['menu_link'];
        }
        $no_html = str_replace('.html', '', $menu['menu_link']);
        // echo '<pre>'; print_r($no_html); echo '</pre>';
        echo '<li'; $ca = '';
        if (isset($data[1])) {
            $child_link = explode("/",$menu['menu_link']);
            if (isset($child_link[1])) {
                if (in_array($child_link[1],$data)) {
                    $ca = ' class="active" ';
                }
            }
        }
        if ($data[0] !== '') {
            if (in_array($menu['menu_link'],$data) || in_array($no_html, $data)) {
                $ca = ' class="active" ';
            }
        } else {
            if ($defpage == trim($menu['menu_link'])) {
                $ca = ' class="active" ';
            }
        }

        echo $ca.'><a href="'.$link.'"'.$ca;
        if (trim($menu['menu_target']) !== '') {
            echo ' target="'.$menu['menu_target'].'" ';
        }
        echo '>'.$menu['menu_name'].'</a>';
        if($sub && in_array((int)$menu['sort'], array_keys($sub))){
            echo '<ul>';
            foreach($sub[(int)$menu['sort']] as $submenu) render_menu_list_item($submenu, $data, $sub);
            echo '</ul>';
        }
        echo '</li>'."\n";
    }