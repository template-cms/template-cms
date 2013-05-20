<?php

    /**
     *	Cleditor plugin
     *
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 3.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    '<a href="#">Cleditor</a>|box',
                    '1.0.0',
                    'Cross browser, extensible, WYSIWYG HTML editor http://premiumsoftware.net/cleditor/',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');

    // Get language file for this plugin
    getPluginLanguage('Cleditor', 'box');

    // Add hooks
    addHook('admin_editor','editor',array());
    addHook('admin_editor_secondary','editor2',array());
    addHook('admin_header','editorHeaders');


    /**
     * Render editor
     *
     * @param string $val editor data
     */
    function editor($val=null) {   
        echo '<div id="editor_panel"></div>';
        echo '<div style="padding:5px;"><textarea id="editor_area" name="editor" >'.$val.'</textarea></div>';
    }


    /**
     * Render secondary editor
     *
     * @param string $val editor data
     */ 
    function editor2($val=null) {        
        echo '<div id="editor_panel"></div>';
        echo '<div style="padding:5px;"><textarea id="editor_area2" name="editor_secondary" >'.$val.'</textarea></div>';
    }


    /**
     * Cleditor Headers
     */
    function editorHeaders() {
            echo '
          <link rel="stylesheet" type="text/css" href="'.getOption('siteurl').'plugins/box/cleditor/cleditor/jquery.cleditor.css" />
          <script type="text/javascript" src="'.getOption('siteurl').'plugins/box/cleditor/cleditor/jquery.cleditor.min.js"></script>
          <script type="text/javascript" src="'.getOption('siteurl').'plugins/box/cleditor/cleditor/jquery.cleditor.plugins.js"></script>    
          <script type="text/javascript">
            // CLEditor plusimage Plugin v1.0
            (function($) {
              // Constants this must be updated with your Path and Text
              var 
              PATH = window.location.host,//without Slash (/) at the end
              //Translation
              VALUETEXT = "'.lang('cleditor_click_to_call_kcfinder').'",
              BUTTONTEXT = "'.lang('cleditor_submit').'",
              TITLETEXT = "'.lang('cleditor_insert_image_from_the_server').'",
              PROMPTTEXT = "'.lang('cleditor_link').'";        
              // Define the plusimage button
              $.cleditor.buttons.plusimage = {
                name: "plusimage",
                image: "plusimage.gif",    
                title: TITLETEXT,    
                command: "insertimage",
                popupName: "Plusimage",
                popupClass: "cleditorPrompt",
                popupContent:         
                  PROMPTTEXT + "<br>" +
                  "<input type=\"text\" readonly=\"readonly\" onclick=\"openKCFinder(this)\" value=\"" + VALUETEXT + "\" style=\"width:300px;cursor:pointer\" /><br>" +
                  "<input type=button value=\"" + BUTTONTEXT + "\">",
                buttonClick: plusimageButtonClick
              };
              // Add the button to the default controls
              $.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls
                .replace("rule image", "rule plusimage");
              // plusimage button click event handler
              function plusimageButtonClick(e, data) {
                // Wire up the submit button click event handler
                $(data.popup).children(":button")
                  .unbind("click")
                  .bind("click", function(e) {
                    // Get the editor
                    var editor = data.editor;
                    // Get the value
                    var $text = $(data.popup).find(":text"),
                      image_path = "http://" + PATH + $text[0].value;
                    // Insert the html
                    editor.execCommand(data.command, image_path, null, data.button);
                    // Reset the text, hide the popup and set focus
                    $text.val(VALUETEXT);
                    editor.hidePopups();
                    editor.focus();
                  });
                }
            })(jQuery);

            $(document).ready(function() {        
                $("#editor_area,#editor_area2").cleditor({   
                    width:        "auto", 
                    height:       400, 
                    controls:    
                                  "bold italic underline strikethrough subscript superscript | font size " +
                                  "style | color highlight removeformat | bullets numbering | outdent " +
                                  "indent | alignleft center alignright justify | undo redo | " +
                                  "rule table image plusimage link unlink | pastetext | source",
                    colors:      
                                  "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                                  "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                                  "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                                  "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                                  "666 900 C60 C93 990 090 399 33F 60C 939 " +
                                  "333 600 930 963 660 060 366 009 339 636 " +
                                  "000 300 630 633 330 030 033 006 309 303",    
                    fonts:       
                                  "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                                  "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
                    sizes:       
                                  "1,2,3,4,5,6,7",
                    styles:      
                                  [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                                  ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
                                  ["Header 6","<h6>"]],
                   
                    useCSS:       false,

                    docCSSFile: "", 
                    bodyStyle: "margin:4px; font:10pt Arial,Verdana; cursor:text"
               });
            });

            function openKCFinder(field) {
              window.KCFinder = {
                  callBack: function(url) {
                      window.KCFinder = null;
                      field.value = url;
                  }
              };

              window.open("'.getOption('siteurl').'plugins/box/cleditor/kcfinder/browse.php?type=image&lang='.getOption('language').'", "kcfinder_textbox",
                  "status=0, toolbar=0, location=0, menubar=0, directories=0, " +
                  "resizable=0, scrollbars=0, width=800, height=600"
              );
            }
           </script>';

        }