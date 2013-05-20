<?php

    /**
     * Plugins admin
     */
    function pluginsAdmin() {
        
        $plugins_info = getPluginInfo();
        $site_url = getSiteUrl(false);           

        // Delete plugin
        // ---------------------------------------------
        if (isGet('delete_plugin')) {            
            // Site administrator cant remove box plugins
            if ($plugins_info[strtolower(str_replace("Plugin", "", get('delete_plugin')))]['privilege'] !== 'box') {
                
                // Run plugin uninstaller
                runPluginUninstall($plugins_info[strtolower(str_replace("Plugin", "", get('delete_plugin')))]['id']);

                deleteFile('../data/plugins/'.get('delete_plugin').'.xml');
                redirect('index.php?id=plugins');                               
            } 
        }

        include 'templates/backend/PluginsTemplate.php';
    }