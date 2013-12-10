<!-- Users_add -->
<?php
    htmlAdminHeading(lang('users_create_new_user'));
    htmlFormOpen('');
    htmlFormInput(array('name'=>'login','style'=>'width:300px;'), lang('users_login')); 
    htmlFormHidden('token', $_SESSION['token']);
    htmlNbsp(3); if(isset($errors['users_this_user_alredy_exists'])) echo '<span class="error">'.$errors['users_this_user_alredy_exists'].'</span>';
                 if(isset($errors['users_empty_login'])) echo '<span class="error">'.$errors['users_empty_login'].'</span>';

    htmlFormInput(array('type'=>'password','name'=>'password','style'=>'width:300px;'), lang('users_password'));
    htmlNbsp(3); if(isset($errors['users_empty_password'])) echo '<span class="error">'.$errors['users_empty_password'].'</span>';
    
    htmlFormInput(array('name'=>'email','style'=>'width:300px;'), lang('users_email'));    
    htmlSelect(array('admin'=>lang('users_role_admin'),'user'=>lang('users_role_user')), array('name'=>'role','value'=>'','style'=>'width:200px;'), lang('users_role'));
    htmlBr(2);
    htmlFormClose(true, array('name'=>'register','value'=>lang('users_register')));
?>
<!-- /Users_add -->