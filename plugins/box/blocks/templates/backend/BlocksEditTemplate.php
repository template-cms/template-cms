<?php
    if($blocks_data !== null) { 
        htmlAdminHeading(lang('blocks_editing'));
        htmlFormOpen('index.php?id=themes&sub_id=blocks&action=edit_block&filename='.get('filename'));
        htmlFormHidden('blocks_old_name', get('filename'));

        if(isset($errors['blocks_empty_name'])) $error_style = 'border:1px solid #FA7660'; else $error_style = '';
        htmlFormInput(array('name'=>'blocks_editor_name','style'=>$error_style,'value'=>$post_name),lang('blocks_name'));
        if(isset($errors['blocks_empty_name'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['blocks_empty_name'].'</span>';
        if(isset($errors['blocks_exists'])) echo '&nbsp;&nbsp;&nbsp;<span class="error">'.$errors['blocks_exists'].'</span>';

        htmlBr(2);
        htmlMemo('blocks_editor',array('style'=>'width:100%;height:400px;','class'=>'source-editor'),toText($blocks_data));
        htmlBr(1);

        htmlFormButton(array('value'=>lang('blocks_save'),'name'=>'edit_blocks'));
        htmlNbsp();
        htmlFormButton(array('value'=>lang('blocks_save_and_exit'),'name'=>'edit_blocks_and_exit'));

        htmlFormClose();
    } else {
        echo '<div class="message-error">'.lang('blocks_does_not_exist').'</div>';
    }
?>