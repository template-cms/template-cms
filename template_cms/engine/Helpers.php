<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Helpers module
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
     * This function found helpers in helpers folder and initalize them.
     *
     * @param string $dir Helpers directory
     */
    function initHelpers($dir) {
        if (is_dir($dir)) {
            $dir_handle = opendir($dir);
            while (($file = readdir($dir_handle)) !== false ) {
                // Helper file must be with Suffix Helper.php its !important
                if (strpos($file, 'Helper.php', 1)) {
                    include_once $dir.'/'.$file;
                }
            }
            closedir($dir_handle);
        }
    }
