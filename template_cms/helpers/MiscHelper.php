<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Misc Helper
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
      * Convert bytes in 'kb','mb','gb','tb','pb'
      *
      * @param integer $bytes Number of bytes.
      * @return string
      */
if ( ! function_exists('byteFormat')) {
    function byteFormat($bytes)	{
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),2).' '.$unit[$i];
    }
}

    /**
     * Show flash message
     *
     * @param string $message Message to display
     * @param string $type Message type. Default is Ok
     * @param integer $time Time in seconds. Default is 2000
     */
if ( ! function_exists('flashMessage')) {
    function flashMessage($message, $type = 'ok', $time = 2000) {
        switch ($type) {
            case 'ok':
                echo '<div class="message-ok">'.$message.'</div>
                      <script type="text/javascript">setTimeout(\'$(".message-ok").slideUp("slow")\', '.$time.'); </script>';
            break;
            case 'warning':
                echo '<div class="message-warning">'.$message.'</div>
                      <script type="text/javascript">setTimeout(\'$(".message-warning").slideUp("slow")\', '.$time.'); </script>';
            break;
            case 'error':
                echo '<div class="message-error">'.$message.'</div>
                      <script type="text/javascript">setTimeout(\'$(".message-error").slideUp("slow")\', '.$time.'); </script>';
            break;
        }
    }
}

    /**
     * Eval function
     *
     * @param array $mathes Results of search.
     * @return mixed
     */
if ( ! function_exists('obEval')) {
    function obEval($mathes) {
        ob_start();
        eval($mathes[1]);
        $mathes = ob_get_contents();
        ob_end_clean();
        return $mathes;
    }
}


    /**
     * Get format date
     *
     * @param integer $date Unix timestamp
     * @param string $format Date format
     * @return integer
     */
if ( ! function_exists('dateFormat')) {
    function dateFormat($date,$format='') {
        if ($format != '') {
            return date($format,(int)$date);
        } else {
            return date(TEMPLATE_CMS_DATE_FORMAT,(int)$date);
        }
    }
}

    /**
     * Compress CSS. Thanks Stephen Clay <steve@mrclay.org> for some regular expressions.
     *
     * @param string $buffer css  
     * @return string
     */
if ( ! function_exists('compressCSS')) {
    function compressCSS($buffer) {        
        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        // Remove tabs, spaces, newlines, etc.
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        // Preserve empty comment after '>' http://www.webdevout.net/css-hacks#in_css-selectors
        $buffer = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $buffer);

        // Preserve empty comment between property and value
        // http://css-discuss.incutio.com/?page=BoxModelHack
        $buffer = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $buffer);
        $buffer = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $buffer);
        
        // Remove ws around { } and last semicolon in declaration block
        $buffer = preg_replace('/\\s*{\\s*/', '{', $buffer);
        $buffer = preg_replace('/;?\\s*}\\s*/', '}', $buffer);

        // Remove ws surrounding semicolons
        $buffer = preg_replace('/\\s*;\\s*/', ';', $buffer);

        // Remove ws around urls
        $buffer = preg_replace('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $buffer);

        // Remove ws between rules and colons
        $buffer = preg_replace('/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"])/x', '$1$2:$3', $buffer);

        // Minimize hex colors
        $buffer = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $buffer);

        // Replace any ws involving newlines with a single newline
        $buffer = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $buffer);

        return $buffer;
    }
}

    /**
     * Compress HTML
     *
     * @param string $buffer html
     * @return string
     */
if ( ! function_exists('compressHTML')) {
    function compressHTML($buffer) {   
        return preg_replace('/^\\s+|\\s+$/m', '', $buffer);
    }
}