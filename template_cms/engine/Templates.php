<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Templates module
     * 
     *  Include box plugins: system,themes,menus,blocks
     *  User template actions call with templateHook's
     * 
     *	@package TemplateCMS
     *  @subpackage Engine
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


    // Get action
    $component = getCommand();
    
    // Get data from uri
    $uri_data = getParams();
    
    
    /**
     * User template hook
     *
     * @param string $hook Hook name
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function templateHook($hook,$render = true) {
        if ($render) runHook($hook); else return runHookC($hook);
    }


    /**
     * Get site name
     *
     * @global string $system_site_name Site name
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getSiteName($render = true) {
        global $system_site_name;
        if ($render) echo $system_site_name; else return $system_site_name;
    }


    /**
     * Get site theme
     *
     * @global string $current_theme Site theme
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getSiteTheme($render = true) {
        global $current_theme;
        if ($render) echo $current_theme; else return $current_theme;
    }


    /**
     * Get site timezone
     *
     * @global string $system_timezone Site timezone
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getSiteTimezone($render = true) {
        global $system_timezone;
        if ($render) echo $system_timezone; else return $system_timezone;
    }


    /**
     * Get default page
     *
     * @global string $defpage Page is loaded by default
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getDefaultPage($render = true) {
        global $defpage;
        if ($render) echo $defpage; else return $defpage;
    }  


    /**
     * Get Page title
     *
     * @global string $component Component name
     * @global array $uri_data URI data
     */
    function getTitle() {
        global $component,$uri_data;

        // Run hook output component
        echo runHookC($component.'_title', array($uri_data));
    }


    /**
     * Get page description
     *
     * @global string $component Component name
     * @global array $uri_data URI data
     */
    function getDescription() {

        global $component, $uri_data, $system_site_description;

        // Run hook output component
        $description = runHookC($component.'_description', array($uri_data));
        
        if (isset($description[0])) {
            if (trim($description[0]) !== '') {
                echo toText($description);
            } else {
                echo toText($system_site_description);
            }
        } else {
            echo toText($system_site_description);
        }
    }


    /**
     * Get page keywords
     *
     * @global string $component Component name
     * @global array $uri_data URI data
     * @global string $system_site_keywords Site keywords
     */
    function getKeywords() {

        global $component, $uri_data, $system_site_keywords;

        // Run hook output component
        $keywords = runHookC($component.'_keywords', array($uri_data));

        if (isset($keywords[0])) {
            if (trim($keywords[0]) !== '') {
                echo toText($keywords);
            } else {
                echo toText($system_site_keywords);
            }
        } else {
            echo toText($system_site_keywords);
        }
    }


    /**
     * Get site slogan
     *
     * @global string $system_site_slogan Site slogan
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getSiteSlogan($render = true) {
        global $system_site_slogan;
        if ($render) echo $system_site_slogan; else return $system_site_slogan;
    }


    /**
     * Get page content
     *
     * @global string $component Component name
     * @global array $uri_data URI data
     */
    function getContent() {
        global $component, $uri_data;

        // Run hook output component
        runHookP($component.'_content', array($uri_data));
    }    


    /**
     * Get compressed template
     *
     * @global string $component Component name
     * @global array $uri_data URI data
     * @global string $current Themes current site theme
     * @return mixed
     */
    function getTemplate() {

        global $component, $uri_data, $current_theme;
        
        $template = runHookC($component.'_template', array($uri_data));

        // Check whether is there such a template in the current theme
        // else return default template: index
        // also compress template file :)
        if (fileExists('themes/'.$current_theme.'/'.$template.'Template.php')) {            
            if ( ! file_exists('themes/'.$current_theme.'/minify.'.$template.'Template.php') or filemtime('themes/'.$current_theme.'/'.$template.'Template.php') > filemtime('themes/'.$current_theme.'/minify.'.$template.'Template.php')) {
                $buffer = file_get_contents('themes/'.$current_theme.'/'.$template.'Template.php');
                $buffer = compressHTML($buffer);
                file_put_contents('themes/'.$current_theme.'/minify.'.$template.'Template.php', $buffer);
            }
            return 'minify.'.$template;
        } else {
            if ( ! fileExists('themes/'.$current_theme.'/minify.indexTemplate.php') or filemtime('themes/'.$current_theme.'/indexTemplate.php') > filemtime('themes/'.$current_theme.'/minify.indexTemplate.php')) {
                $buffer = file_get_contents('themes/'.$current_theme.'/indexTemplate.php');
                $buffer = compressHTML($buffer);
                file_put_contents('themes/'.$current_theme.'/minify.indexTemplate.php', $buffer);
            }
            return 'minify.index';
        }        
    }


    /**
     *  Load compressed css file
     *
     *  @param string $dir Directory
     *  @param string $filename CSS file name
     *  @param string $media Media type
     *  @param string $query_string Query string
     *  @global string $site_url Site url
     */
    function loadCSS($dir, $filename, $media = 'all', $query_string = '') {
        global $site_url;

        // Prepare query string
        if ($query_string !== '') $qs = '?'.$query_string; else $qs = '';

        if (fileExists($dir.$filename)) {
            if ( ! fileExists($dir.'minify.'.$filename) or filemtime($dir.$filename) > filemtime($dir.'minify.'.$filename)) {
                
                // Get css file
                $buffer = file_get_contents($dir.$filename);

                // And compress it!
                $buffer = compressCSS($buffer);

                // Save compressed css file
                file_put_contents($dir.'minify.'.$filename, $buffer);
                
                // GZip styles 
                if (TEMPLATE_CMS_GZIP_STYLES) file_put_contents("compress.zlib://".$dir."minify.".$filename.".gz",$buffer);
            }
            echo '<link rel="stylesheet" type="text/css" href="'.$site_url.$dir.'minify.'.$filename.$qs.'" media="'.$media.'" />';
        }
    }


    /**
     * Load javascript file(s)
     *
     * @todo minify
     * @global string $site_url Site url
     * @param string $dir Directory
     * @param mixed $filename JS file name
     */
    function loadJS($dir, $filename) {
        global $site_url;

        // Check if $filename is array of js files then go through this array and load them
        if (is_array($filename)) {
            foreach ($filename as $file) {
                echo '<script type="text/javascript" src="'.$site_url.$dir.$file.'"></script>'."\n";
            }
        } else {
            echo '<script type="text/javascript" src="'.$site_url.$dir.$filename.'"></script>';
        }
    }


    /**
     * Load theme template file
     *
     * @param string $filename Template file name
     */
    function loadTemplate($filename) {
        if (fileExists($filename)) {
            include $filename;
        }
    }


    /**
     * Get site url
     *
     * @global string $site_url Site url
     * @return string
     */
    function getSiteUrl($render = true) {
        global $site_url;
        if ($render) echo $site_url; else return $site_url;
    }


    /**
     * Get site menu
     *
     * @global array $uri_data URI data
     * @param string $file Menu file name
     */
    function getSiteMenu($file) {
        global $uri_data;
        runHookP('menus_site', array($file,$uri_data));
    }


    /**
     * Get block
     *
     * @param string $file Block file name
     */
    function getBlock($file) {
        runHookP('blocks_site', array($file));
    }


    /**
     * Get elapsed time
     *
     * @global integer $start_time Start time value
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getElapsedTime($render = true) {
        global $start_time;
        $result_time = microtime(true) - $start_time;
        if ($render) printf("Elapsed time %.3f seconds", $result_time); else return sprintf("%.3f", $result_time);
    }


    /**
     * Get memory usage
     *
     * @param boolean $render Displays the result of the function in the browser or not
     */
    function getMemoryUsage($render = true) {
        if (function_exists('memory_get_usage')) {
            $memory_usage = memory_get_usage();
        } else if (substr(PHP_OS,0,3) == 'WIN') {
            // Windows 2000 workaround
            $output = array();
            exec('pslist ' . getmypid() , $output);
            $memory_usage = trim(substr($output[8],38,10));
        } else {
            $memory_usage = '';
        }
        if ($render) {
            printf('Memory usage: '.byteFormat($memory_usage));
        } else {
            return $memory_usage;
        }
    }


    /**
     * Get copyright information
     */
    function getCopyright($render = true) {
        $copyright = lang('system_powered_by').' <a href="'.TEMPLATE_CMS_SITEURL.'" target="_blank">Template CMS</a> '.TEMPLATE_CMS_VERSION;
        if ($render) echo $copyright; else return $copyright;
    }


    // Add new shortcode {siteurl}
    addShortCode('siteurl','returnSiteUrl');
    function returnSiteUrl() { global $site_url; return $site_url; }