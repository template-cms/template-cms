<?php


    /**
     * Plugins installer admin
     */
    function pluginsInstallerAdmin() {
        
        $site_url = getSiteUrl(false);

        // Install plugin
        // ---------------------------------------------
        if (isGet('install')) {                          
            copyFile('../plugins/'.basename(lowercase(get('install')),'plugin.xml').'/'.'install/'.get('install'), '../data/plugins/'.get('install'));                                            
                                    
            $plugin_xml = getXML('../data/plugins/'.get('install'));
          
            include '../'.$plugin_xml->plugin_location;                        

            // Run plugin installer
            runPluginInstall(lowercase(str_replace(array("Plugin", ".xml"), "", get('install'))));

            unset($plugin_xml);

            redirect('index.php?id=plugins');                                  
        }


        // Delete plugin from server
        // ---------------------------------------------
        if (isGet('delete_plugin_from_server')) {
            deleteDir('../plugins/'.get('delete_plugin_from_server'));
            redirect('index.php?id=plugins&sub_id=pluginsinstaller');
        }

        // Get installed plugins from /data/plugins/
        $plugins_installed = array();

        // Get new plugins from /plugins/
        $plugins_new = array();

        // Plugins to install
        $plugins_to_intall = array(); 

        // Compare them and show plugins to install
        $plugins_new = listOfFiles('../plugins','.xml');
        $plugins_installed = listOfFiles('../data/plugins','.xml');
        $plugins_to_install = array_diff($plugins_new, $plugins_installed);

        // Create array of plugins to install
        $count = 0;
        foreach ($plugins_to_install as $plugin) {
            $plg_path = '../plugins/'.lowercase(basename($plugin,'Plugin.xml')).'/install/'.$plugin;
            if (file_exists($plg_path)) {
                $plugins_to_intall[$count]['path']   = $plg_path;
                $plugins_to_intall[$count]['plugin'] = $plugin;
                $count++;
            }            
        }

        include 'templates/backend/PluginsInstallerTemplate.php';
    }