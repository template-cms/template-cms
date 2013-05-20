<?php
    htmlFormOpen('index.php?id=themes');
    htmlSelect($themes_folders,array('name'=>'themes','style'=>'width:200px;'),lang('themes_theme'),$current_theme);
    htmlNbsp(2);
    htmlFormClose(true,array('value'=>lang('themes_save'),'name'=>'save_theme'));
    htmlNbsp(2);
    htmlAdminHeading(lang('themes_current_theme').': '.$current_theme, 2);
    htmlNbsp();
?>
<table width="100%">
    <tr>
        <td width="50%">
            <?php htmlHeading(lang('themes_templates'), 3); ?>
        </td>
        <td width="50%">
            <?php htmlHeading(lang('themes_styles'), 3); ?>
        </td>
    </tr>
    <tr>
        <td width="50%" valign="top">            
            <table>
            <?php
                foreach($themes_templates as $template) {
            ?>            
            <tr>
                <td><?php htmlButtonEdit(basename($template,'Template.php'), 'index.php?id=themes&action=edit_template&file='.$template); ?></td>
                <td><?php htmlNbsp();htmlButtonEdit('+1', 'index.php?id=themes&action=clone_template&file='.$template,lang('themes_clone')); ?></td>
                <td><?php htmlButtonDelete('X', 'index.php?id=themes&action=delete_template&file='.$template,lang('themes_delete')); ?></td>                                
            </tr>
            <?php    
                }
            ?>
            </table>        
            <?php
                htmlButton(lang('themes_templates_create'),'index.php?id=themes&action=add_template');
            ?>            
        </td>
        <td width="50%" valign="top">
            <table>
            <?php
                foreach($themes_styles as $style) {
            ?>  
            <tr>          
                <td><?php htmlButtonEdit(basename($style,'.css'), 'index.php?id=themes&action=edit_css&file='.$style); ?></td>
                <td><?php htmlNbsp();htmlButtonEdit('+1', 'index.php?id=themes&action=clone_css&file='.$style,lang('themes_clone')); ?></td>
                <td><?php htmlButtonDelete('X', 'index.php?id=themes&action=delete_css&file='.$style,lang('themes_delete')); ?></td>            
            </tr>          
            <?php
                }
            ?>
            </table>
            <?php   
                htmlButton(lang('themes_styles_create'),'index.php?id=themes&action=add_css');
            ?>
            
        </td>
    </tr>
</table>
<?php    
    if(count(getComponents()) > 2) // all exept Pages and Sitemap plugins
        htmlAdminHeading(lang('themes_components_theme'), 2);
    // Its mean that you can add your own actions for this plugin
    runHook('admin_themes_extra_template_actions');
?>

