<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Uri module
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


    // Plugins registed as component array
    global $plugins_components;

    // Set default component
    $default_component = 'pages';
    
  
    /**
     *	Get uri and explode command/param1/param2
     *
     *  @return array
     */	
    function getUri() {

        // Get request uri and current script path
        $requestURI = explode('/', $_SERVER['REQUEST_URI']);
        $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

        // Delete script name
        for ($i = 0; $i < sizeof($scriptName); $i++) {
            if ($requestURI[$i] == $scriptName[$i]) {
                unset($requestURI[$i]);
            }
        }
        
        // Get all the values of an array
        $uri = array_values($requestURI);

        // Ability to pass parameters
        foreach ($uri as $i => $u) {
          if (isset($uri[$i])) { 
            $pos = strrpos($uri[$i], "?"); if ($pos === false) { 
              $uri[$i] = sanitizeURL(str_replace('.html', '', $uri[$i])); 
            } else { 
              $uri[$i] = sanitizeURL(str_replace('.html', '', substr($uri[$i], 0, $pos))); 
            } 
          }
        }
    
        // Return uri segments
        return $uri;
    }


    /**
     *	Get command/component from registed components
     *
     *  @global string $default_component Default component/command
     *  @global array $plugins_components Array of plugins components
     *  @return array
     */
    function getCommand() {

        global $default_component, $plugins_components;

        // Get URI
        $uri = getUri();

        if ( ! isset($uri[0])) {
            $uri[0] = $default_component;
        } else {
            if ( ! in_array($uri[0], $plugins_components)  ) {
                $uri[0] = $default_component;
            } else {
                $uri[0] = $uri[0];
            }
        }
        return $uri[0];
    }


    /**
     *	Get uri parammeters
     *
     *  @global string $default_component Default component/command
     *  @return array
     */
    function getParams() {

        global $default_component;

        //Init data array
        $data = array();              

        // Get URI
        $uri = getUri();

        // http://site.com/ and http://site.com/index.php same main home pages
        if ( ! isset($uri[0])) {
            $uri[0] = '';
        }              

        // param1/param2
        if ($uri[0] !== $default_component) {
            if (isset($uri[1])) {
                $data[0] = $uri[0];
                $data[1] = $uri[1];
                // Some more uri parts :)
                // site.ru/part1/part2/part3/part4/part5/part6/
                if (isset($uri[2])) $data[2] = $uri[2];
                if (isset($uri[3])) $data[3] = $uri[3];
                if (isset($uri[4])) $data[4] = $uri[4];
                if (isset($uri[5])) $data[5] = $uri[5];
            } else { // default
                $data[0] = $uri[0];
            }
        } else {
            // This is good for box plugin Pages
            // parent/child
            if (isset($uri[2])) {
                $data[0] = $uri[1];
                $data[1] = $uri[2];                
            } else { // default
                $data[0] = $uri[1];
            }
        }
        return $data;
    }