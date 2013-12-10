<!-- Pages_add -->
<?php
    htmlAdminHeading(lang('pages_creating'));
    htmlFormOpen('index.php?id=pages&action=add_page');
   
    if(isset($errors['pages_empty_name']) or isset($errors['pages_exists'])) $error_style = 'border:1px solid #FA7660'; else $error_style = '';
    htmlFormInput(array('name'=>'page_name','style'=>$error_style,'value'=>$post_name),lang('pages_name'));
    htmlFormHidden('token', $_SESSION['token']); 
    if(isset($errors['pages_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_empty_name'].'</span>';
    if(isset($errors['pages_exists'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_exists'].'</span>';
    
    
    if(isset($errors['pages_empty_title'])) $error_style2 = 'border:1px solid #FA7660'; else $error_style2 = '';
    htmlFormInput(array('name'=>'page_title','style'=>$error_style2,'value'=>$post_title),lang('pages_title'));
    if(isset($errors['pages_empty_title'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['pages_empty_title'].'</span>';
    
    htmlFormInput(array('name'=>'page_description','value'=>$post_description),lang('pages_description'));
    htmlFormInput(array('name'=>'page_keywords','value'=>$post_keywords),lang('pages_keywords'));
    htmlBr();
?>
<table width="500px" align="center">
    <tr>
        <td width="230px">
            <?php htmlSelect($pages_array,array('name'=>'pages','style'=>'width:200px;'),lang('pages_parent'),$parent_page); ?>
        </td>
        <td>
            <?php htmlSelect($templates_array,array('name'=>'templates','style'=>'width:200px;'),lang('pages_template'),$post_template); ?>
        </td>
    </tr>
</table>       
<?php
    htmlBr(2);
    runHookP('admin_editor',array($post_content));
    runHook('admin_pages_editadd_extra');
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

    htmlFormButton(array('value'=>lang('pages_save'),'name'=>'add_page'));
    htmlNbsp();
    htmlFormButton(array('value'=>lang('pages_save_and_exit'),'name'=>'add_page_and_exit'));
    htmlFormClose();
?>
<!-- /Pages_add -->