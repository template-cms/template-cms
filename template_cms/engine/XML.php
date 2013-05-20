<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	XMLDB API module. 
     * 
     *  Use SimpleXML and xPath to navigate through elements and attributes in an XML document.
     *  @link http://www.w3schools.com/xml/
     *  @link http://www.w3schools.com/xpath/
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



    /**
     * Create safe xml data. Removes dangerous characters for xml database.
     *
     * @param string $str String
     */     
    function safeXMLdata($str) {
        return xssClean($str);
    } 


    /**
     * Get XML file
     *
     * @param string $file File name
     * @return array
     */
    function getXML($file, $force = false) {
        // For CMS API XML file force method
        if ($force) {
            $xml = file_get_contents($file);
            $data = simplexml_load_string($xml);
            return $data;
        } else {
            if (fileExists($file)) {
                $xml = file_get_contents($file);
                $data = simplexml_load_string($xml);
                return $data;
            } else {
                return false;
            }
        }
    }

    
    /**
     * Create new XML database
     *
     * @param string $file XML database file
     */
    function createXMLdb($file) {
        createFile($file.'.xml','<?xml version="1.0" encoding="UTF-8"?><root><options><option_autoincrement>0</option_autoincrement></options></root>');
    }


    /**
     * Delete XML database file
     *
     * @param string $file Path to xml database file
     */
    function dropXMLdb($file) {
        deleteFile($file);
    }


    /**
     * Get XML database. Simple load XML file and return its name and XML object
     *
     * @param array $file XML file
     * @return mixed
     */
    function getXMLdb($file) {
        if (fileExists($file)) {
            $data = array('xml_object'   => getXML($file),
                          'xml_filename' => $file);
            return $data;
        } else {
            return false;
        }
    }


    /**
     * Format XML and save
     *
     * @param array $xml_db Array of database name and XML object
     */
    function saveXML($xml_db) {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;

        // Save new xml data to xml file only if loadXML successful.        
        // Preventing the destruction of the database by unsafe data.
        // note: If loadXML !successful then saveXML() add&save empty record.
        //       This record cant be removed by deleteXMLRecord[Where]() Problem solved by hand removing...
        //       Possible solution: modify deleteXMLRecord[Where]() or prevent add&saving of such records.
        // the result now: database cant be destroyed :)
        if ($dom->loadXML($xml_db['xml_object']->asXML())) {              
            $dom->save($xml_db['xml_filename']);                  
        } else {            
            // report about errors...
        }                

    }


    /**
     * Add new record to xml file
     * Example:
     *   insertXMLRecord($xml_db,'my_record_name',array('field1_name'=>'field1_value',
     *                                                  'field2_name'=>'field1_value'));
     *
     * @param array $xml_db Array database name and XML object
     * @param string $record_name Name of new record
     * @param array $fields Record fields to insert
     * @param array $record_attr Attributes of new record
     */
    function insertXMLRecord($xml_db, $record_name, $fields = array(), $record_attr = array()) {
       
        // Find autoincrement option
        $inc = selectXMLRecord($xml_db,"options/option_autoincrement");
            
        // Add record
        $node = $xml_db['xml_object']->addChild(safeXMLdata($record_name));

        // Add attribute for new record: unique ID
        $node->addAttribute('id', $inc+1);

        // Update autoincrement
        updateXMLRecordWhere($xml_db, "options", array('option_autoincrement' => $inc+1));

        // If some else record attributes exists add them
        if (count($record_attr) !== 0) {
            foreach ($record_attr as $key => $value) {
                $node->addAttribute($key,safeXMLdata($value));
            }
        }

        // If exists fields to insert then insert them
        if (count($fields) !== 0) {
            foreach ($fields as $key => $value) {                
                $node->addChild($key,safeXMLdata($value));
            }
        }

        // Save xml file
        saveXML($xml_db);
    }


    /**
     * Select record(s) in xml file
     * Example:
     *   selectXMLRecord($xml_db,"//menu");       // Select one record
     *   selectXMLRecord($xml_db,"//menu",'all'); // Select all records
     *   selectXMLRecord($xml_db,"//menu",2);     // Select 2 records
     *   selectXMLRecord($xml_db,"//menu",2,2);   // Select 2 records after record id 2
     *
     * @param array $xml_db Array database name and XML object
     * @param string $query XPath query
     * @param integer $row_count Row count. To select all records write 'all'
     * @param integer $offset Offset
     * @return object
     */
    function selectXMLRecord($xml_db, $query, $row_count = null, $offset = null) {
        
        $tmp = $xml_db['xml_object']->xpath($query);

        $data = array();
        
        if ($row_count == null) {
            return isset($tmp[0])? $tmp[0]: null;
        } else {
            if ($row_count == 'all') {
                foreach($tmp as $record) {
                    $data[] = $record;
                }                
                return $data;
            } else {
                foreach ($tmp as $record) {
                    $data[] = $record;
                }
                // If offset is null slice array from end else from begin
                if ($offset == null) {
                    return array_slice($data, -$row_count, $row_count);
                } else {
                    return array_slice($data, $offset, $row_count);
                }
            }
        }

    }


    /**
     * Select fields in xml records. Records get by selectXMLRecord() function.
     * Example:
     *   selectXMLFields($xml_db,array('id','name','date'), 'date', 'DESC'));
     *
     * @param object $records Records
     * @param array $fields Array of fields to select
     * @param string $order_by Order by field
     * @param sring $order Order type ASC or DESC. Default is ASC
     * @return array
     */
    function selectXMLfields($records, $fields, $order_by, $order = 'ASC') {
        $count = 0;
        if (count($records) > 0) {
            foreach ($records as $key => $record) {
                foreach ($fields as $field) {
                    $record_array[$count][$field] = $record->$field;
                }
                $record_array[$count]['id'] = $record['id'];
                $record_array[$count]['sort'] = $record->$order_by;
                $count++;
            }
            $s = subval_sort($record_array, 'sort', $order);
            return $s;
        }
    }

  
    /**
     * Delete current record in xml file
     * Example:
     *   deleteXMLRecord($xml_db,'my_record_name',1);
     *
     * @param array $xml_db Array database name and XML object
     * @param string $record_name Name of new record
     * @param integer $id Record ID
     */
    function deleteXMLRecord($xml_db, $record_name, $id) {
        // xPath query
        $query = "//".$record_name."[@id='".$id."']";

        // Find record to delete
        $xml_arr = selectXMLRecord($xml_db,$query);

        // If its exists then delete it
        if (count($xml_arr) !== 0) {
            unset($xml_arr[0]);
        }

        // Save xml file
        saveXML($xml_db);
    }


    /**
     * Delete with xPath query record in xml file
     *
     * @param array $xml_db Array database name and XML object
     * @param string $query xPath query
     */
    function deleteXMLRecordWhere($xml_db, $query) {
        
        // Find record to delete
        $xml_arr = selectXMLRecord($xml_db,$query);
        
        // If its exists then delete it
        if (count($xml_arr) !== 0) {
            unset($xml_arr[0]);
        }

        // Save xml file
        saveXML($xml_db);
    }


    /**
     * Update record with xPath query in XML file
     * Example:
     *   updateXMLRecordWhere($xml_db,"//name[@id='1']",array('field1_name'=>'new_field1_value',
     *                                                        'field2_name'=>'new_field1_value'));
     *
     * @param array $xml_db Array database name and XML object
     * @param string $query XPath query
     * @param array $fields Record fields to udpate
     */
    function updateXMLRecordWhere($xml_db, $query, $fields = array()) {
     
        // Find record to delete
        $xml_arr = selectXMLRecord($xml_db,$query);

        // If its exists then delete it
        if (count($fields) !== 0) {
            foreach ($fields as $key => $value) {
                $xml_arr->$key = safeXMLdata($value);
            }
        }

        // Save xml file
        saveXML($xml_db);
    }


    /**
     * Update current record in XML file
     * Example:
     *   updateXMLRecord($xml_db,'my_record_name',1,array('field1_name'=>'new_field1_value',
     *                                                    'field2_name'=>'new_field1_value'));
     *
     * @param array $xml_db Array database name and XML object
     * @param string $record_name Record name to update
     * @param integer $id Record ID
     * @param array $fields Record fields to udpate
     */
    function updateXMLRecord($xml_db, $record_name, $id, $fields = array()) {
        // xPath query
        $query = "//".$record_name."[@id='".(int)$id."']";

        // Find record to delete
        $xml_arr = selectXMLRecord($xml_db,$query);
				
        // If its exists then update it
        if (count($fields) !== 0) {
            foreach ($fields as $key => $value) {
                // Clear record fileds
                unset($xml_arr->$key);
                $xml_arr->addChild($key,safeXMLdata($value));
            }
        }

        // Save xml file
        saveXML($xml_db);
    }


    /**
     * Get last record id
     *
     * @param array $xml_db Array database name and XML object
     * @param string $record_name Record name
     * @return object
     */
    function lastXMLRecordId($xml_db, $record_name) {
        $query = "//".$record_name."[last()]";
        $data = $xml_db['xml_object']->xpath($query);
        return $data[0]['id'];
    }


    /**
     * Get count of records
     *
     * @param array $xml_db Array database name and XML object
     */
    function countXMLRecords($xml_db) {
        return count($xml_db['xml_object'])-1;
    }

    
    /**
     * Get information about xml database
     *
     * @param array $xml_db Array database name and XML object
     * @return array
     */
    function getXMLdbInfo($xml_db) {
    	$data = array();
    	$data['database_name'] 		  = basename($xml_db['xml_filename'],'.xml');
    	$data['database_size']        = filesize($xml_db['xml_filename']);
    	$data['database_last_change'] = filemtime($xml_db['xml_filename']);
    	$data['database_last_access'] = fileatime($xml_db['xml_filename']);    	
    	$data['records_count']        = countXMLRecords($xml_db);    	
    	$data['records_last_id']      = (int)lastXMLRecordId($xml_db,'root/node()');    	
    	$data['fields_count']         = count(selectXMLRecord($xml_db, '//node()[@*]'));
    	return $data;
    }