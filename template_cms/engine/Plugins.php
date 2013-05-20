<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Plugins API module
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


    // Plugins hooks
    $plugins = array();

    // Registed plugins
    $plugins_info = array();

    // Plugins registed as component
    $plugins_components = array();

    //Plugins Filters
    $plugins_filters = array();

    
    /**
     * Initializing plugins
     *
     * @global string $admin_path Admin or frontend access
     * @param string $dir Plugins directory
     */
    function initPlugins($dir) {
        global $admin_path;
        
        if (is_dir($dir)) {
            // Create a plugins array from plugins installer files PluginName.xml
            $plugins_array = listFiles($dir, 'Plugin.xml');
            if (isset($plugins_array)) {
                $count = 0;
                foreach ($plugins_array as $current_plugin) {
                    $plugin_xml = getXML($admin_path.TEMPLATE_CMS_DATA_PATH.'plugins/'.$current_plugin);
                    $plugin_data[$count]['location'] = (string)$plugin_xml->plugin_location;
                    $plugin_data[$count]['frontend'] = (string)$plugin_xml->plugin_frontend;
                    $plugin_data[$count]['backend']  = (string)$plugin_xml->plugin_backend;
                    $plugin_data[$count]['status']   = (string)$plugin_xml->plugin_status;
                    $plugin_data[$count]['priority'] = (int)$plugin_xml->plugin_priority;
                    $count++;
                }
            }

            // Sort plugins by priority.
            // Note: priority 1 - 10 its for box plugins, others must have priority more then 11
            $plugins_data = subval_sort($plugin_data, 'priority');

            // Now include plugins from plugins array
            // Check where plugin must loaded: Frontend or Backend or Both
            // If plugin is active then load it to the system.
            foreach ($plugins_data as $data) {
                if ($data['status'] == 'active') {
                    if (($data['frontend'] == 'yes') and ($data['backend'] == 'yes')) {
                        include_once $admin_path.$data['location'];
                    } else {
                        if (($admin_path == '../') and ($data['backend'] == 'yes')) {
                            include_once $admin_path.$data['location'];
                        } else {
                            if (($admin_path == '') and ($data['frontend'] == 'yes')) {
                                include_once $admin_path.$data['location'];
                            }
                        }
                    }
                }
            }            
        } 
    }


    /**
     * Get real plugin ID
     *
     * @param string $file File name
     * @return string 
     */
    function getPluginId($file) {
        return strtolower(basename($file, 'Plugin.php'));
    }


    /**
     * Get real plugin name
     *
     * @param string $file File name
     * @return string 
     */
    function getPluginFilename($file) {        
        return fileName($file);
    }


    /**
     * Get registred plugins
     *
     * @global array $plugins_info Array of registred plugins
     * @return array
     */
    function getPluginInfo() {
        global $plugins_info;
        return $plugins_info;
    }


    /**
     * Get plugins hooks
     *
     * @global array $plugins Array of registred plugins
     * @return array
     */
    function getHooks() {
        global $plugins;
        return $plugins;
    }


    /**
     * Get registred components
     *
     * @global array $plugins_components Array or registred plugins as component
     * @return array
     */
    function getComponents() {
        global $plugins_components;
        return $plugins_components;
    }


    /**
     * Get registred filters
     *
     * @global array $plugins_filters Array of plugins filters
     * @return array
     */
    function getFilters() {
        global $plugins_filters;
        return $plugins_filters;
    }


    /**
     * Get plugin localization
     *
     * @global array $lang Array of localizations
     * @param string $key Localization name
     * @return string
     */
    function lang($key) {
        global $lang;
        return $lang[$key];
    }


    /**
     * Get plugin admin
     *
     * @param string $plug Plugin Name
     * @param string $alt_folder Alternative plugin folder
     * @global boolean $admin Admin access only
     */
    function getPluginAdmin($plug, $alt_folder = null) {
        global $admin;

        // Plugin admin extension
        $ext = 'Admin.php';        

        // Plugin admin can be loaded only in backend
        if ($admin) {            
            if ( ! empty($alt_folder)) {
                $folder = $alt_folder.'/'.strtolower($plug);
            } else {
                $folder = strtolower($plug);
            }

            $path = TEMPLATE_CMS_PLUGINS_PATH.$folder.'/'.$plug.$ext;

            if (fileExists($path)) {
                include $path;
            }
        }        
    }


    /**
     * Get plugin language file
     *
     * @param string $plug Plugin Name
     * @param string $alt_folder Alternative plugin folder
     * @global string $admin_path Admin access or frontend
     * @global string $system_language Site language
     * @global string $lang Plugin localizations
     */
    function getPluginLanguage($plug, $alt_folder = null) {
        
        global $admin_path,$system_language,$lang;    

        // Check maybe plugin languages files in alternative folder
        if ( ! empty($alt_folder)) {
            $folder = $alt_folder.'/'.strtolower($plug);
        } else {
            $folder = strtolower($plug);
        }
        
        $path = $admin_path.'plugins/'.$folder.'/languages/'.$plug.'-'.$system_language.'-Lang.php';

        // Load plugin languages file
        if (fileExists($path)) {
            include $path;
        } else {
            include $admin_path.'plugins/'.$folder.'/languages/'.$plug.'-en-Lang.php';
        }
    }   


    /**
     * Register new plugin in system
     *
     * @param string $id Plugin ID
     * @param string $filename Plugin file name
     * @param string $name Plugin name
     * @param string $ver  Plugin version
     * @param string $desc Plugin description
     * @param string $auth Plugin author
     * @param string $auth_uri Plugin author uri
     * @param string $admin Plugin admin function
     * @param string $component Plugin as component
     * @global array $plugin_info Array of registred plugins
     * @global array $plugins_components Array of of registred component
     */
    function registerPlugin($id, $filename, $name, $ver = null, $desc = null, $auth = null, $auth_uri = null, $admin = null, $component = null) {
        
        global $plugins_info,$plugins_components;

        // Set privilege name or name|box
        $plg_name = explode("|",$name);

        $plg_real_name = $plg_name[0];
        if (isset($plg_name[1])) {
            $plg_privilege = $plg_name[1];
        } else {
            $plg_privilege = '';
        }

        // Register plugin in global plugins array.
        $plugins_info[$id] = array(
          'id'          => $id,
          'name'        => $plg_real_name,
          'privilege'   => $plg_privilege,
          'version'     => $ver,
          'description' => $desc,
          'author'      => $auth,
          'author_uri'  => $auth_uri,
          'admin_data'  => $admin,
          'filename'    => $filename
        );

        // Add plugin as component
        // Plugin - component will be available at the link sitename/component_name
        // Example:
        //    www.sitename.com/guestbook
        //    www.sitename.com/news
        if ( ! empty($component)) {
            $plugins_components[] = $component;
        }
    }


    /**
     * Get registed users plugins count
     *
     * @global array $plugin_info Array of registred plugins
     * @return integer
     */
    function getPluginsCount() {    
    	global $plugins_info;
    	foreach ($plugins_info as $plugin) if ($plugin['privilege'] !== 'box') $users_plugins[] = $plugin['id']; else $users_plugins = array();    	   	    	
    	return count($users_plugins);    	
    }


    /**
     * Add hook
     *
     * @param string $hook_name Hook name
     * @param string $added_function Added function
     * @param array $args Arguments
     * @global array $plugins Array of plugins hooks
     */
    function addHook($hook_name, $added_function, $args = array()) {
        
        global $plugins;

        // Add hook to plugins hooks array
        $plugins[] = array(
                        'hook'     => $hook_name,
                        'function' => $added_function,
                        'args'     => (array)$args
        );
    }


    /**
     * Run hook
     *
     * @param string $action Plugin hook name
     * @global array $plugins Array of plugins hooks
     */
    function runHook($action) {

        global $plugins;

        // Load hook from global plugins hooks array.
        foreach ($plugins as $hook) {
            if ($hook['hook'] == $action) {
                call_user_func_array($hook['function'], $hook['args']);
            }
        }
    }


    /**
     * Run hook with parameters
     *
     * @param string $action Plugin hook name
     * @param array $args Arguments
     * @global array $plugins Array of plugins hooks
     */
    function runHookP($action, $args = array()) {
        
        global $plugins;

        // Load hook from global plugins hooks array with args
        foreach ($plugins as $hook)	{
            if ($hook['hook'] == $action) {
                call_user_func_array($hook['function'], $args);
            }
        }
    }


    /**
     * Run hook that can return data
     *
     * @param string $action Plugin hook name
     * @param array $args Arguments
     * @global array $plugins Array of plugins hooks
     * @return mixed
     */
    function runHookC($action, $args = array()) {
        
        global $plugins;

        $data = array();

        // Load hook from global plugins hooks array with args
        foreach ($plugins as $hook)	{
            if ($hook['hook'] == $action) {
                $data = call_user_func_array($hook['function'], $args);
            }
        }

        return $data;
    }


    /**
     * Install plugin
     * 
     * @param string $plugin_name Plugin to install
     */
    function runPluginInstall($plugin_name) {
        call_user_func($plugin_name.'Install');       
    }


    /**
     * Uninstall plugin
     * 
     * @param string $plugin_name Plugin to uninstall
     */
    function runPluginUninstall($plugin_name) {
        call_user_func($plugin_name.'Uninstall');
    }


    /**
     * Apply filters
     *
     * @global array $plugins_filters Plugin filters array
     * @param string $filter_name The name of the filter hook.
     * @param mixed $value The value on which the filters hooked.
     * @return mixed
     */
    function applyFilters($filter_name, $value) {
        
        global $plugins_filters;

        $args = array_slice(func_get_args(), 2);

        if ( ! isset($plugins_filters[$filter_name])) {
            return $value;
        }
        
        foreach ($plugins_filters[$filter_name] as $priority => $functions) {
            if ( ! is_null($functions)) {
                foreach ($functions as $function) {
                    $all_args = array_merge(array($value), $args);
                    $function_name = $function['function'];
                    $accepted_args = $function['accepted_args'];
                    if ($accepted_args == 1) {
                        $the_args = array($value);
                    } elseif ($accepted_args > 1) {
                        $the_args = array_slice($all_args, 0, $accepted_args);
                    } elseif ($accepted_args == 0) {
                        $the_args = null;
                    } else {
                        $the_args = $all_args;
                    }
                    $value = call_user_func_array($function_name, $the_args);
                }
            }
        }
        return $value;
    }


    /**
     * Add filter
     *
     * @global array $plugins_filters Plugin filters array
     * @param string $filter_name The name of the filter to hook the $function_to_add to.
     * @param callback $function_to_add The name of the function to be called when the filter is applied.
     * @param integer $priority Function to add priority - default is 10.
     * @param integer $accepted_args The number of arguments the function accept default is 1.
     * @return boolean
     */
    function addFilter($filter_name, $function_to_add, $priority = 10, $accepted_args = 1) {
        global $plugins_filters;

        // Check that we don't already have the same filter at the same priority. Thanks to WP :)
        if (isset($plugins_filters[$filter_name]["$priority"])) {
            foreach ($plugins_filters[$filter_name]["$priority"] as $filter) {
                if ($filter['function'] == $function_to_add) {
                    return true;
                }
            }
        }
        $plugins_filters[$filter_name]["$priority"][] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
        ksort($plugins_filters[$filter_name]["$priority"]);
        return true;
    }  