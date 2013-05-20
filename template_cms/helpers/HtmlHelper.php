<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	HTML Helper
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
     * Create a link in button style.
     *
     * @param string $txt Button name
     * @param string $url Button link
     * @param string $extra extra data
     * @param string $title Title if is empty then title = $txt
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlButton')) {
    function htmlButton($txt, $url, $title=null, $render=true, $class='btn', $extra='') {
        if (empty($title)) $title = $txt;                
        if ($render) echo '<span class="'.$class.'"><a href="'.$url.'" '.$extra.' title="'.$title.'">'.$txt.'</a></span>'; else return $output;
    }
}

    /**
     * Create a link in edit button style.
     *
     * @param string $txt Button name
     * @param stting $url Button link
     * @param string $title Title if is empty then title = $txt
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlButtonEdit')) {
    function htmlButtonEdit($txt, $url, $title=null, $render=true) {
        if (empty($title)) $title = $txt;
        if ($render) echo '<span class="btn-edit"><a href="'.$url.'" title="'.$title.'">'.$txt.'</a></span>'; else return $output;
    }
}

    /**
     * Create a link in delete button style.
     *
     * @param string $txt Button name
     * @param stting $url Button link
     * @param string $title Title if is empty then title = $txt
     * @param boolean $render If this option is true then render html object else return it
     * @param boolean $enable Delete button status
     */
if ( ! function_exists('htmlButtonDelete')) {
    function htmlButtonDelete($txt, $url, $title=null, $render=true, $enable=true) {
        if (empty($title)) $title = $txt;
        if ($render) { 
            if ($enable) {
                echo '<span class="btn-delete"><a href="'.$url.'" title="'.$title.'" onclick="return confirmDelete(\''.$title.'\')">'.$txt.'</a></span>';
            } else {
                echo '<span class="btn-delete-disable"><a href="'.$url.'" title="'.$title.'">'.$txt.'</a></span>';
            }
        } else {
            return $output;
        }              
    }
}

    /**
     * Create a href link
     *
     * @param string $txt Name
     * @param stting $url Link
     * @param string $title Title if is empty then title = $txt
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlLink')) {
     function htmlLink($txt, $url, $target=null, $title=null, $render=true) {
        if (empty($title)) $title = $txt;
        if ($target !== '') { $tg = 'target="'.$target.'"'; }
        $output = '<a href="'.$url.'" '.$tg.' title="'.$title.'">'.$txt.'</a>';
        if ($render) echo $output; else return $output;
    }
}

    /**
     * Create <h> tag
     *
     * @param string $txt Text
     * @param integer $h Number [1-6]
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlHeading')) {
    function htmlHeading($txt, $h=1, $render=true) {
        $output = '<h'.(int)$h.'>'.$txt.'</h'.(int)$h.'>';
        if ($render) echo $output; else return $output;
    }
}

    /**
     * Create page header in Admin area
     *
     * @param string $txt Text
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlAdminHeading')) {
    function htmlAdminHeading($txt, $render=true) {
        $output = '<div class="admin-heading">'.$txt.'</div>';
        if ($render) echo $output; else return $output;
    }
}

    /**
     * Create br tags
     *
     * @param integer $num Count of line break tag
     */
if ( ! function_exists('htmlBr')) {
    function htmlBr($num = 1) {
        echo str_repeat("<br />",(int)$num);
    }
}

    /**
     * Create &nbsp;
     *
     * @param integer $num Count of &nbsp;
     */
if ( ! function_exists('htmlNbsp')) {
    function htmlNbsp($num = 1) {
        echo str_repeat("&nbsp", (int)$num);
    }
}

    /**
     * Create arrow
     *
     * @param string $direction Arrow direction [up,down,left,right]
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlArrow')) {
    function htmlArrow($direction, $render=true) {
        switch ($direction) {
            case "up": $output    = '<span class="arrow">&uarr;</span>';
            case "down": $output  = '<span class="arrow">&darr;</span>';
            case "left": $output  = '<span class="arrow">&larr;</span>';
            case "right": $output = '<span class="arrow">&rarr;</span>';
        }
       	if ($render) echo $output; else return $output;
    }
}

    /**
     * Create img
     *
     * @param array $attr Image attributes
     * @param string $src Image url
     * @param boolean $render If this option is true then render html object else return it
     */
if ( ! function_exists('htmlImg')) {
	function htmlImg($src, $attr=array(), $render=true) {
        $output = '<img ';
        $output .= 'src="'.$src.'" ';
        if (isset($attr['align']))   $output .= 'align="'.$attr['align'].'" ';
        if (isset($attr['border']))  $output .= 'border="'.$attr['border'].'" ';
        if (isset($attr['width']))   $output .= 'width="'.$attr['width'].'" ';
        if (isset($attr['heigth']))  $output .= 'height="'.$attr['height'].'" ';
		if (isset($attr['style']))   $output .= 'style="'.$attr['style'].'" ';
        if (isset($attr['alt']))     $output .= 'alt="'.$attr['alt'].'" ';  else  $output .= 'alt=""';
        $output .= ' />';
        if ($render) echo $output; else return $output;
    }
}


    /**
     * Open new form
     *
     * @param string $action Form action
     * @param string $method Form method, default is POST
     * @param boolean $upload Enable upload form
     */
if ( ! function_exists('htmlFormOpen')) {
    function htmlFormOpen($action, $method='post', $upload=FALSE) {
        if ($upload) $enctype = 'enctype="multipart/form-data"'; else $enctype = '';
        $output = '<form action="'.$action.'" method="'.$method.'" '.$enctype.' >';
        echo $output;
    }
}

    /**
     * Create form input
     *
     * @param $attr array Attributes
     * @param $label string Input label
     */
if ( ! function_exists('htmlFormInput')) {
	function htmlFormInput($attr, $label=null) {
        if ( ! isset($attr['type'])) $type = 'text'; else $type = $attr['type'];
        $output = '<input type="'.$type.'"';
        if (isset($attr['value'])) $output .= ' value = "'.$attr['value'].'"';
        if (isset($attr['name']))  $output .= ' name = "'.$attr['name'].'"';
        if (isset($attr['id']))    $output .= ' id = "'.$attr['id'].'"';
        if (isset($attr['class'])) $output .= ' class = "'.$attr['class'].'"';
        if (isset($attr['length']))$output .= ' length = "'.$attr['length'].'"';
        if (isset($attr['size']))  $output .= ' size = "'.$attr['size'].'"'; else $output .= ' size = "100"';
        if (isset($attr['style'])) $output .= ' style = "'.$attr['style'].'"';
        $output .= ' />';
        
        if ( ! empty($label)) $output_label = '<label>'.$label.'</label><br />'; else $output_label = '';
        
        if ($type == 'hidden') {
            echo $output;            
        } else {
            echo $output_label.$output.htmlBr();        
        }            
    }
}

    /**
     * Create hidden text input
     *
     * @param string $name Input name
     * @param string $value Input value
     */
if ( ! function_exists('htmlFormHidden')) {
    function htmlFormHidden($name, $value) {
        echo '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
    }
}

    /**
     * Create a select list
     *
     * @param array $options Array of options
     * @param array $attr Html attributes
     * @param string $label Select list label
     * @param string $selected Selected option
     */
if ( ! function_exists('htmlSelect')) {
    function htmlSelect($options, $attr=array(), $label=null, $selected=null) {
        if ( ! empty($label)) $output_label = '<label>'.$label.'</label><br />'; else $output_label ='';

        if ( ! empty($selected)) $selected_key = $selected; else $selected_key = '';

        if (isset($attr['name']))  $name  = ' name = "'.$attr['name'].'"';   else $name = '';
        if (isset($attr['style'])) $style = ' style = "'.$attr['style'].'"'; else $style = '';

        $output_begin = '<select '.$name.$style.'>';
        $options_output = '';
        if (count($options) > 0) {
            foreach ($options as $key => $value) {
	            if (is_int($key)) $key = $value; // its hack for files
	            if ($selected_key == $key) $curr = ' selected '; else $curr = '';
	            $options_output .= '<option value="'.$key.'" ';
	            $options_output .= $curr.'>'.$value.'</option>';
            }
        }
        $output_end = '</select>';
        echo $output_label.htmlBr(1);
        echo $output_begin.$options_output.$output_end;
    }
}


    /**
     * Create textarea
     *
     * @param string $name Textarea name
     * @param array $attr Textarea attributes
     * @param string $value Textarea value
     */
if ( ! function_exists('htmlMemo')) {
    function htmlMemo($name, $attr=array(), $value=null, $label=null) {
        $output = '<textarea name="'.$name.'"';
        if (isset($attr['style'])) $output .= ' style = "'.$attr['style'].'"';
        if (isset($attr['class'])) $output .= ' class = "'.$attr['class'].'"';
        $output .= ' >';
        $output .= $value;
        $output .= '</textarea>';

        if ( ! empty($label)) $output_label = '<br /><label>'.$label.'</label><br />'; else $output_label = '';
        
        echo $output_label.$output;
    }
}


    /**
     * Create form file input
     *
     * @param string $name Input name
     */
if ( ! function_exists('htmlFormFile')) {
    function htmlFormFile($name, $size) {
        htmlFormInput(array('type'=>'file','name'=>$name,'size'=>$size));
    }
}
	

    /**
     * Close form
     *
     * @param boolean $submit Close form with submit button
     * @param array $attr Attributes for submit button
     */
if ( ! function_exists('htmlFormClose')) {
    function htmlFormClose($submit=false, $attr=null) {
        if ($submit) {
            $output = '<input type="submit" class="submit" ';
            if (isset($attr['name']))  $output .=' name="'.$attr['name'].'"';
            if (isset($attr['value'])) $output .=' value="'.$attr['value'].'"';
            $output .= 'class="wymupdate" />'; // class wymupdate is hack for WYMeditor
        } else {
            $output = '';
        }
        $output .= '</form>';
        echo $output;
    }
}


    /**
     * Basic form button
     *
     * @param array $attr Attributes for button
     */
if ( ! function_exists('htmlFormButton')) {
    function htmlFormButton($attr) {
        $output  = '<input';
        $output .= ' type="submit" class="submit"';
        if (isset($attr['name']))  $output .=' name="'.$attr['name'].'"';
        if (isset($attr['value'])) $output .=' value="'.$attr['value'].'"';
        $output .= ' >';
        echo $output;
    }
}


    /**
     * Create form checkbox
     *
     * @param array $attr Attributes for checkbox
     */
if ( ! function_exists('htmlFormCheckbox')) {
    function htmlFormCheckbox($attr) {
        $output = '<input';
        $output .= ' type="checkbox"';
        if (isset($attr['name']))  $output .=' name="'.$attr['name'].'"';
        if (isset($attr['id']))    $output .= ' id = "'.$attr['id'].'"';
        if (isset($attr['class'])) $output .= ' class = "'.$attr['class'].'"';
        if (isset($attr['style'])) $output .= ' style = "'.$attr['style'].'"';
        if (isset($attr['checked'])) $output .= ' checked = "'.$attr['checked'].'"';        
        $output .= ' >';
        echo $output;
    }
}


    /**
     * Message window. Based on jQuery Reveal Plugin. See: template-cms.js
     * Example:
     *    htmlButton('hello button','#',null,true,'submit-link','data-reveal-id="hello-window-id"');    
     *    htmlMsgWindow('hello-window-id','hello world');  
     * @param integer $id window id
     * @param string $str window message
     */
if ( ! function_exists('htmlMsgWindow')) {
    function htmlMsgWindow($id, $str) {
        echo '<div id="'.$id.'" class="reveal-modal">
                 <div id="'.$id.'-content">
                     '.$str.'
                 </div>
                 <a class="close-reveal-modal">&#215;</a>
              </div>';
    }
}