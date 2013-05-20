<?php

    /**
     *	Sitemap plugin
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
                    '<a href="#">Sitemap</a>|box',
                    '1.0',
                    'Show sitemap',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '',
                    'sitemap');


   // Add hooks as component / template hooks
   addHook('sitemap_content','sitemapContent',array());
   addHook('sitemap_template','sitemapTemplate',array());
   addHook('sitemap_title','sitemapTitle',array());   

   // Get language file for this plugin
   getPluginLanguage('Sitemap', 'box');

   /**
    * Get template
    * @return string
    */
   function sitemapTemplate() {
       return 'index';
   }

   /**
    * Get title
    * @return string
    */
   function sitemapTitle() {
        return lang('system_sitemap'); 
   }

   /**
    * Get sitemap content
    */
   function sitemapContent() {
        $pages_path = TEMPLATE_CMS_DATA_PATH . 'pages/';
        $pages_list = listFiles($pages_path,'.xml');

        $pages_array = array();

        $count = 0;
        
        foreach ($pages_list as $file) {

            // Get pages without error404.xml page
            if ($file !== 'error404.xml') {                        
                $data = getXML($pages_path . $file);
                $pages_array[$count]['title'] = html_entity_decode($data->title, ENT_QUOTES, 'UTF-8');
                $pages_array[$count]['parent'] = $data->parent;
                $pages_array[$count]['date'] = $data->date;
                $pages_array[$count]['slug'] = $data->slug;

                if (isset($data->parent)) {
                    $c_p = $data->parent;
                } else {
                    $c_p = '';
                }

                if ($c_p != '') {
                    $parent_data = getXML($pages_path . $data->parent . '.xml');
                    if(isset($parent_data->title)) {
                        $parent_title = $parent_data->title;
                    } else {
                        $parent_title = '';
                    }
                    $pages_array[$count]['sort'] = $parent_title . ' ' . $data->title;
                } else {
                    $pages_array[$count]['sort'] = $data->title;
                }
                $parent_title = '';
                $count++;
            }
        }
        $pages = subval_sort($pages_array, 'sort');

        
        include 'templates/frontend/SiteMapTemplate.php';
    }
    

    /**
     * Create sitemap
     */
    function createSitemap() {
        $pages_path = '../' . TEMPLATE_CMS_DATA_PATH . 'pages/';
        $pages_list = listFiles($pages_path,'.xml');

        $pages_array = array();

        $count = 0;
        foreach ($pages_list as $file) {

            // Get pages without error404.xml page
            if ($file !== 'error404.xml') {
                $data = getXML($pages_path . $file);
                $pages_array[$count]['title'] = html_entity_decode($data->title, ENT_QUOTES, 'UTF-8');
                $pages_array[$count]['parent'] = $data->parent;
                $pages_array[$count]['date'] = $data->date;
                $pages_array[$count]['slug'] = $data->slug;

                if (isset($data->parent)) {
                    $c_p = $data->parent;
                } else {
                    $c_p = '';
                }

                if ($c_p != '') {
                    $parent_data = getXML($pages_path . $data->parent . '.xml');
                    if(isset($parent_data->title)) {
                        $parent_title = $parent_data->title;
                    } else {
                        $parent_title = '';
                    }
                    $pages_array[$count]['sort'] = $parent_title . ' ' . $data->title;
                } else {
                    $pages_array[$count]['sort'] = $data->title;
                }
                $parent_title = '';
                $count++;
            }
        }
        $pages = subval_sort($pages_array, 'sort');
        
        $map = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $map .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($pages as $page) {
            if ($page['parent'] != '') {
                $parent = $page['parent'].'/';
            } else {
                $parent = '';
            }
            $map .= "\t".'<url>'."\n\t\t".'<loc>'.getSiteUrl(false).$parent.$page['slug'].'</loc>'."\n\t\t".'<lastmod>'.date("Y-m-d",(int)$page['date']).'</lastmod>'."\n\t\t".'<changefreq>weekly</changefreq>'."\n\t\t".'<priority>1.0</priority>'."\n\t".'</url>'."\n";
        }
        $map .= '</urlset>';
        createFile('../sitemap.xml',$map);
    }