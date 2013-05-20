<!-- Pages_edit -->

<?php if($slug_to_edit == 'error404') { ?>    
    <script>$().ready(function() { $(".slug").addClass("error-none"); });</script>
<?php } ?>

<?php
    htmlAdminHeading(lang('pages_editing'));
    htmlFormOpen('index.php?id=pages&action=edit_page&filename='.get('filename'));
    htmlFormInput(array('value'=>get('filename'),'name'=>'page_old_name','type'=>'hidden'));
    
    if(isset($errors['pages_empty_name']) or isset($errors['pages_exists'])) $error_style = 'border:1px solid #FA7660'; else $error_style = '';
    echo '<span class="slug">';
    htmlFormInput(array('name'=>'page_name','style'=>$error_style,'value'=>toText($slug_to_edit)),lang('pages_name'));
    echo '</span>';
    if(isset($errors['pages_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_empty_name'].'</span>';
    if(isset($errors['pages_exists'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_exists'].'</span>';
    

    if(isset($errors['pages_empty_title'])) $error_style2 = 'border:1px solid #FA7660'; else $error_style2 = '';
    htmlFormInput(array('name'=>'page_title','style'=>$error_style2,'value'=>toText($title_to_edit)),lang('pages_title'));
    if(isset($errors['pages_empty_title'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_empty_title'].'</span>';

    htmlFormInput(array('value'=>toText($description_to_edit),'name'=>'page_description'),lang('pages_description'));
    htmlFormInput(array('value'=>toText($keywords_to_edit),'name'=>'page_keywords'),lang('pages_keywords'));
    htmlFormHidden('old_parent', toText($xml->parent));
    htmlBr(1);
?>
<table width="500px" align="center">
    <tr>
        <?php if(get('filename') != 'error404') { ?>
        <td width="230px">
            <?php htmlSelect($pages_array,array('name'=>'pages','style'=>'width:200px;'),lang('pages_parent'),$parent_page); ?>
        </td>
        <?php } ?>
        <td>
            <?php htmlSelect($templates_array,array('name'=>'templates','style'=>'width:200px;'),lang('pages_template'),$template); ?>
        </td>
    </tr>
</table> 
<?php
    htmlBr(2);
    runHookP('admin_editor',array($to_edit));
?>
<div id="pages-other">
<div id="pages-other-box">

<?php
    echo '<div class="date">';
    echo '<div style="float:left;">';
    echo lang('pages_year');    
    htmlSelect($years, array('name'=>'year'), '', $date[0]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('pages_month');    
    htmlSelect($month, array('name'=>'month'), '', $date[1]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('pages_day');    
    htmlSelect($days, array('name'=>'day'), '', $date[2]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('pages_hours');    
    htmlSelect($hours, array('name'=>'hour'), '', $date[3]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('pages_minutes');    
    htmlSelect($minutes, array('name'=>'minute'), '', $date[4]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('pages_seconds');
    htmlSelect($seconds, array('name'=>'second'), '', $date[5]);
    echo '</div>';
    echo '</div>';
    echo '<br style="clear:both;" />';
?>
</div>
<div id="pages-other-toggle"><?php echo lang('pages_other'); ?></div>
</div>
<?php
    echo '<br style="clear:both;" />';

    htmlBr(1);
        

    htmlFormButton(array('value'=>lang('pages_save'),'name'=>'edit_page'));
    htmlNbsp();
    htmlFormButton(array('value'=>lang('pages_save_and_exit'),'name'=>'edit_page_and_exit'));
    htmlFormClose();
    
?>

<!-- /Pages_edit -->