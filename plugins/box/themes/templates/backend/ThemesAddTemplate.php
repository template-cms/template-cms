<?php
    htmlAdminHeading(lang('themes_creating_template'));
    htmlFormOpen('index.php?id=themes&action=add_template');

    if(isset($errors['themes_empty_name'])) $error_style = 'border:1px solid #FA7660'; else $error_style = '';
    htmlFormInput(array('name'=>'themes_editor_name','style'=>$error_style,'value'=>$post_name),lang('themes_name'));
    if(isset($errors['themes_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['themes_empty_name'].'</span>';
    if(isset($errors['themes_template_exists'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['themes_template_exists'].'</span>';

    htmlBr(2);
    htmlMemo('themes_editor',array('style'=>'width:100%;height:400px;'),$post_themes_editor);
    include 'ThemesSnippets.php';

    htmlFormHidden('token', $_SESSION['token']); 
    htmlFormButton(array('value'=>lang('themes_save'),'name'=>'add_template'));
    htmlNbsp();
    htmlFormButton(array('value'=>lang('themes_save_and_exit'),'name'=>'add_template_and_exit'));
    htmlFormClose();

?>