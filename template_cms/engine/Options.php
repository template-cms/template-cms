<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Options API module
     *
     *	@package TemplateCMS
     *  @subpackage Engine
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0.4
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */


    /**
     * Add a new option
     * 
     * @param mixed $option Name of option to add.
     * @param mixed $value Option value.
     * @global array $options_xml Options array.
     */
    function addOption($option, $value = null) {
        
        global $options_xml;
        
        if (is_array($option)) {
            foreach ($option as $k => $v) {
                if ( ! isset($options_xml['xml_object']->$k)) {
                    insertXMLRecord($options_xml, $k, array('value' => $v));
                }           
            }
        } else {
            if ( ! isset($options_xml['xml_object']->$option)) {
                insertXMLRecord($options_xml, $option, array('value' => $value));
            }       
        }
    }


    /**
     * Update option value
     * 
     * @param mixed $option Name of option to update.
     * @param mixed $value Option value.
     * @global array $options_xml Options array.
     */
    function updateOption($option, $value = null) {
        
        global $options_xml;
        
        if (is_array($option)) {
            foreach ($option as $k => $v) {
                if (isset($options_xml['xml_object']->$k)) {
                    updateXMLRecordWhere($options_xml, $k, array('value' => $v));       
                }
            }
        } else {
            if (isset($options_xml['xml_object']->$option)) {
                updateXMLRecordWhere($options_xml, $option, array('value' => $value));      
            }
        }       
    }


    /**
     * Get option value
     *
     * @param string $option Name of option to get.
     * @global array $options_xml Options array.
     */
    function getOption($option) {
        global $options_xml;        
        return $options_xml['xml_object']->$option->value;      
    }


    /**
     * Delete option
     *
     * @param string $option Name of option to get.
     * @global array $options_xml Options array.
     */      
    function deleteOption($option) {
        
        global $options_xml;
        
        if (isset($options_xml['xml_object']->$option)) {
            deleteXMLRecordWhere($options_xml, "//$option"); 
        }
    }