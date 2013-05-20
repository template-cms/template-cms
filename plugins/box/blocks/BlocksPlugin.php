<?php

    /**
     *	Blocks plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 1.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    '<a href="index.php?id=themes&sub_id=blocks">Blocks</a>|box',
                    '1.0',          
                    'Blocks manager plugin',
                    'Awilum',           
                    'http://awilum.webdevart.ru/',
                    'blocksAdmin');      


    // Get language file for this plugin
    getPluginLanguage('Blocks', 'box');

    // Add hooks
    addHook('admin_themes_second_navigation','adminSecondNavigation',array('themes',lang('blocks_submenu'),'blocks'));

    // Add template hook
    addHook('blocks_site','blocksSite',array());

    // Include Admin
    getPluginAdmin('Blocks', 'box');

    // Add shortcode {block name="blockname"}
    addShortcode('block','blockContent');


    /**
     * Template function to display blocks
     * @param string $file blockfilename
     */
    function blocksSite($file) {
        $block_path = TEMPLATE_CMS_DATA_PATH.'blocks/'.$file.'.php';
        if(fileExists($block_path)) {
            include $block_path;
        } else {
            if(isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                echo '<b>Block <u>'.$file.'</u> is not found!</b>';
            }
        }
    }

    /**
     * Returns block content for shortcode {block name="blockname"}
     *
     * @param array $attributes block filename
     */
    function blockContent($attributes) {        
        $block_path = TEMPLATE_CMS_DATA_PATH.'blocks/'.$attributes['name'].'.php';

        if(fileExists($block_path)) {
            ob_start();        
            include $block_path;
            $block_contents = ob_get_contents();
            ob_end_clean();
            return $block_contents;
        } else {
            if(isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                return '<b>Block <u>'.$attributes['name'].'</u> is not found!</b>';
            }
        }
    }