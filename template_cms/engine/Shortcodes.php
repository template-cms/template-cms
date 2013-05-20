<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Template CMS Shortcodes API
     * 
     *  The Shortcode API s a simple regex based parser that allows you to replace simple bbcode-like tags
     *  within a HTMLText or HTMLVarchar field when rendered into a content.
     *
     *  Examples of shortcode tags:
     *
     *     {shortcode}
     *     {shortcode parameter="value"}
     *     {shortcode parameter="value"}Enclosed Content{/shortcode}
     *
     *
     *  Example of escaping shortcodes:
     *
     *     {{shortcode}}
     *
     *
     *  @package TemplateCMS
     *  @subpackage Engine
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 - 2012 Romanenko Sergey / Awilum
     *  @version $Id$
     *  @since 2.0.5
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


    // Shortcode tags array
    $shortcode_tags = array();


    /**
     * Add shortcode
     *
     * @param string $shortcode Shortcode tag to be searched in content.
     * @param callable $callback_function The callback function to replace the shortcode with.
     */
    function addShortcode($shortcode, $callback_function) {
        global $shortcode_tags;       
        if (is_callable($callback_function)) $shortcode_tags[$shortcode] = $callback_function;        
    }


    /**
     * Parse a string, and replace any registered shortcodes within it with the result of the mapped callback.
     *
     * @param string $content Content
     * @return string
     */
    function parseShortcode($content) {
        
        global $shortcode_tags;     
        
        if ( ! $shortcode_tags) return $content;
             
        $shortcodes = implode('|', array_map('preg_quote', array_keys($shortcode_tags)));
        $pattern    = "/(.?)\{($shortcodes)(.*?)(\/)?\}(?(4)|(?:(.+?)\{\/\s*\\2\s*\}))?(.?)/s";
             
        return preg_replace_callback($pattern, 'handleShortcode', $content);
    }
         

    function handleShortcode($matches) {
        
        global $shortcode_tags;  
        
        $prefix    = $matches[1];
        $suffix    = $matches[6];
        $shortcode = $matches[2];
         
        // Allow for escaping shortcodes by enclosing them in {{shortcode}}
        if ($prefix == '{' && $suffix == '}') {
            return substr($matches[0], 1, -1);
        }
         
        $attributes = array(); // Parse attributes into into this array.
         
        if (preg_match_all('/(\w+) *= *(?:([\'"])(.*?)\\2|([^ "\'>]+))/', $matches[3], $match, PREG_SET_ORDER)) {
            foreach ($match as $attribute) {
                if ( ! empty($attribute[4])) {
                    $attributes[strtolower($attribute[1])] = $attribute[4];
                } elseif ( ! empty($attribute[3])) {
                    $attributes[strtolower($attribute[1])] = $attribute[3];
                }
            }
        }
         
        return $prefix . call_user_func($shortcode_tags[$shortcode], $attributes, $matches[5], $shortcode) . $suffix;
    }