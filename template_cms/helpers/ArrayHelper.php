<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Array Helper
     *
     *	@package TemplateCMS
     *	@subpackage Helpers
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
      * Subval sort
      *
      * @param array $a Array
      * @param string $subkey Key
      * @param string $order Order type DESC or ASC
      * @return array
      */
if ( ! function_exists('subval_sort')) {
    function subval_sort($a, $subkey, $order = null) {
        if (count($a) != 0 || ( ! empty($a))) {
            foreach ($a as $k => $v) {
                $b[$k] = function_exists('mb_strtolower') ? mb_strtolower($v[$subkey]) : strtolower($v[$subkey]);
            }
            if ($order == null || $order == 'ASC') {
                asort($b);
            } else {
                if ($order == 'DESC') {
                    arsort($b);
                }
            }
            foreach ($b as $key => $val) {
                $c[] = $a[$key];
            }
            return $c;
        }
    }
}