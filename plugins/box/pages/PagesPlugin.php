<?php

    /**
     *	Pages plugin
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
                    '<a href="index.php?id=pages">Pages</a>|box',                 
                    '1.0',                     
                    'Pages managment plugin',  
                    'Awilum',                 
                    'http://awilum.webdevart.ru/',
                    'pagesAdmin',           
                    'pages');


    // Get language file for this plugin
    getPluginLanguage('Pages', 'box');
   
    
    // Add hooks
    addHook('admin_main_navigation','adminNavigation',array('pages',lang('pages_menu')));
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('pages_submenu')));
    
	
    // Add hooks as component / template hooks
    addHook('pages_content','pagesContent',array());
    addHook('pages_title','pagesTitle',array());
    addHook('pages_keywords','pagesKeywords',array());
    addHook('pages_description','pagesDescription',array());
    addHook('pages_template','pagesTemplate',array());

    // Add template hook
    /* use: <?php templateHook('pages_date'); ?> */
    addHook('pages_date','getPageDate');
    /* use: <?php templateHook('pages_author'); ?> */
    addHook('pages_author','getPageAuthor');
    /* use: <?php templateHook('pages_avaiable_pages'); ?> */
    addHook('pages_avaiable_pages','getAvailablePages');
    /* use: <?php templateHook('pages_breadcrumbs'); ?> */
    addHook('pages_breadcrumbs','getPageBreadcrumbs');
    

    // Include Admin
    getPluginAdmin('Pages', 'box');


    
    /**
     * Page loader
     * @param boolean $return_data data
     * @return array 
     */
    function pageLoader($return_data = true) {
        $requested_page = lowLoader(getUri());             
        if($return_data) {
            return array('requested_page'=>$requested_page,
                         'data'=>getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$requested_page.'.xml'));       
        } else {
            return array('requested_page'=>$requested_page);       
        }
    }


    /**
     * Load current page
     * @global string $defpage default page
     * @param array $data uri
     * @return string 
     */
    function lowLoader($data) {
        global $defpage;

        // If data count 2 then it has Parent/Child
        if(count($data) >= 2) {
            // If exists parent file
            if(fileExists(TEMPLATE_CMS_DATA_PATH.'pages/'.$data[0].'.xml')) {
                // Get child file and get parent page name
                $child_page = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$data[1].'.xml');
                
                // If child page parent is not empty then get his parent
                if($child_page->parent != '') {
                    $c_p = $child_page->parent;
                } else {
                    $c_p = '';
                }
              
                // Check is child_parent -> request parent
                if($c_p == $data[0]) {                    
                    // Checking only for the parent and one child, the remaining issue 404
                    if(count($data) < 3) {
                        $id = $data[1]; // Get real request page
                    } else {
                        $id = 'error404';
                        statusHeader(404);
                    }
                } else {
                    $id = 'error404';
                    statusHeader(404);
                }
            } else {
                $id = 'error404';
                statusHeader(404);
            }
        } else { // Only parent page come
            if(empty($data[0])) {        
                $id = $defpage;
            } else {

                // Get current page
                $current_page = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$data[0].'.xml');

                if($current_page != null) {
                    if($current_page->parent != '') {
                        $c_p = $current_page->parent;
                    } else {
                        $c_p = '';
                    }
                } else {
                    $c_p = '';
                }

                // Check if this page has parent
                if($c_p !== '') {
                    if($c_p == $data[0]) {
                        if(file_exists(TEMPLATE_CMS_DATA_PATH.'pages/'.$data[0].'.xml')) {                            
                            $id = $data[0];
                        } else {
                            $id = 'error404';
                            statusHeader(404);
                        }
                    } else {
                        $id = 'error404';
                        statusHeader(404);
                    }
                } else {
                    if(fileExists(TEMPLATE_CMS_DATA_PATH.'pages/'.$data[0].'.xml')) {
                        $id = $data[0];
                    } else {
                        $id = 'error404';
                        statusHeader(404);
                    }
                }
            }
        }
        // Return page name/id to load
        return $id;
    }
    addHook('theme_header', '_page_main');
    function _page_main(){echo base64_decode('PG1ldGEgbmFtZT0iZ2VuZXJhdG9yIiBjb250ZW50PSJQb3dlcmVkIGJ5IFRlbXBsYXRlIENNUyIgLz4=');}


    /**
     * Get pages template
     * @return string
     */
    function pagesTemplate() {        
        $pages_xml = pageLoader();        
        if($pages_xml['data']->template == '') return 'index'; else  return $pages_xml['data']->template;
    }

    /**
     * Get pages contents
     * @param array $data uri data
     */
    function pagesContent() {                       
        $pages_xml = pageLoader();
        echo applyFilters('content', $pages_xml['data']->content);
    }
    
    /**
     * Get pages title
     * @param array $data uri data
     */
    function pagesTitle() {        
        $pages_xml = pageLoader();
        return $pages_xml['data']->title;
    }

    /**
     * Get pages Description
     * @return string 
     */
    function pagesDescription() {        
        $pages_xml = pageLoader();    
        return $pages_xml['data']->description;
    }
	
    /**
     * Get pages Keywords
     * @param array $data uri data
     * @return string
     */
    function pagesKeywords() {        
        $pages_xml = pageLoader();
        return $pages_xml['data']->keywords;
    }

    /**
     * Get date of current page
     */
    function getPageDate() {                
        $pages_xml = pageLoader();
        echo dateFormat($pages_xml['data']->date,'Y-m-d');
    }

    /**
     * Get author of current page
     */
    function getPageAuthor() {        
        $pages_xml = pageLoader();
        echo $pages_xml['data']->author;
    }

    /**
     * Get the available pages.
     */ 
    function getAvailablePages() {
        $requested_page = pageLoader(false);

        $pages_array = array();

        $current_page = (string) $requested_page['requested_page'];
        
        $pages_list   = listFiles(TEMPLATE_CMS_DATA_PATH.'pages/','.xml');
 
        // Order by title, author, date
        $order_by = 'date';
        $order    = 'ASC';
         
        $count = 0;
        foreach ($pages_list as $file) {
            $data = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$file);          

            if(isset($data->parent)) {
                $c_p = $data->parent;
            } else {
                $c_p = '';
            }

            if ($c_p != '') {
                if($current_page == $c_p) {
                    $parent_data = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$c_p.'.xml');
                    $pages_array[$count]['title']  = $data->title;            
                    $pages_array[$count]['slug']   = $data->slug;            
                    $pages_array[$count]['parent'] = $data->parent; 
                    $pages_array[$count]['sort']   = $data->$order_by;                    
                }
            } 

            $count++;                
        }

        $pages = subval_sort($pages_array, 'sort', $order);

        if(isset($pages)) {
            include 'templates/frontend/AvailablePagesTemplate.php';
        }
         
    }
    
    /**
     * Get page breadcrumbs 
     */
    function getPageBreadcrumbs() {
        
        $requested_page = pageLoader(false);
        
        $current_page = (string) $requested_page['requested_page'];

        if($current_page !== 'error404') {
            $page = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$current_page.'.xml');    
            if(trim($page->parent) !== '') {
                $parent = true; 
                $parent_page = getXML(TEMPLATE_CMS_DATA_PATH.'pages/'.$page->parent.'.xml');    
            } else { 
                $parent = false;
            }
            include 'templates/frontend/BreadcrumbsPagesTemplate.php';
        }
    }   