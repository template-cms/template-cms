<?php


    // Add navigation hooks
    addHook('admin_top_system_navigation','systemAdminTopNavigation');
    addHook('admin_main_navigation','adminNavigation',array('system',lang('system_menu')));
    addHook('admin_system_second_navigation','adminSecondNavigation',array('system',lang('system_submenu')));


    /**
     * Add urls to admin top navigation
     */
    function systemAdminTopNavigation() {
        echo '<a target="_blank" href="'.getSiteUrl(false).'">'.lang('system_site').'</a>&nbsp;&nbsp;';
        echo '<a href="'.getSiteUrl(false).'admin/index.php?logout=do">'.lang('system_logout').'</a>';
    }
   
    
    /**
     * System plugin admin
     * @global $api_common common api file
     */
    function systemAdmin() {

        $plugins    = getPluginInfo();
        $components = getComponents();
        $filters    = getFilters();
        $hooks      = getHooks();

        $common_api_url = 'http://template-cms.ru/api/basic.xml';
        
        // Check if is exists common_api_url then try to force load only in system plugin area
        if (urlExists($common_api_url) and (isset($_GET['id']) && $_GET['id'] == 'system')) $api_common = getXML($common_api_url,true);

        // Check TEMPLATE CMS API
        // Сheck version
        if ($api_common->TEMPLATE_CMS_VERSION_ID > TEMPLATE_CMS_VERSION_ID) {
            $old_cms_version_message = true;
        } else {
            $old_cms_version_message = false;
        }

        // Get system timezone
        $system_timezone = getOption('timezone');

        
        // Get languages files
        $language_files = listOfFiles('../plugins/box/system/languages/', 'Lang.php');
        foreach ($language_files as $language) {
            $languages_array[] = preg_replace('/(.*)-(.*)-(.*)/', '$2', $language);
        }

        // Get page files
        $page_files = listOfFiles('../'.TEMPLATE_CMS_DATA_PATH.'pages/', '.xml');
        foreach ($page_files as $page) {
            $page_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'pages/'.$page);
            if ($page_xml != null) {
                if (trim($page_xml->slug) !== 'error404') {
                    $pages_array[] = trim($page_xml->slug);
                }
            }
        }

        // Create sitemap
        // ---------------------------------------------
        if (isGet('sitemap')) {
            if ('create' == get('sitemap')) {
                createSiteMap();           
                flashMessage(lang('system_sitemap_created'));
            }            
        }

        // Set maintenance mod
        // ---------------------------------------------
        if (isGet('maintenance')) {
            if ('on' == get('maintenance')) {                            
                updateOption('maintenance_status','on');
                redirect('index.php?id=system');
            }
            if ('off' == get('maintenance')) {                                
                updateOption('maintenance_status','off');
                redirect('index.php?id=system');
            }
        }

        // Edit main settings
        // ---------------------------------------------
        if (isPost('edit_main_settings')) {

            updateOption(array('sitename'=>post('site_name'),
                               'keywords'=>post('site_keywords'),
                               'description'=>post('site_description'),
                               'slogan'=>post('site_slogan'),
                               'defaultpage'=>post('site_default_page')));

            redirect('index.php?id=system');
        }

        // Edit system settings
        // ---------------------------------------------
        if (isPost('edit_system_settings')) {
                  
            
            // Add trailing slashes
            $_site_url = post('system_url');
            if ($_site_url[strlen($_site_url)-1] !== '/' ) {
                $_site_url = $_site_url.'/';
            }

            updateOption(array('siteurl'  => $_site_url,
                               'timezone' => post('system_timezone'),
                               'language' => post('system_language'),
                               'maintenance_message'=>post('site_maintenance_message')));
            
            redirect('index.php?id=system');
        }

        // Its mean that you can add your own actions for this plugin
        runHook('admin_system_extra_actions');

        // Include timezone-s
        include '../'.TEMPLATE_CMS_DATA_PATH.'system/timezone.php';

        // Display system template
        include 'templates/backend/SystemTemplate.php';
    }