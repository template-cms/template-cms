<!-- Users_edit -->
<?php
    // Show template for exist user else show error
    if($user !== null) {
    
    htmlAdminHeading(lang('users_edit_profile'));
?>

<div style="float:left;">    
<?php
    htmlFormOpen('');
    htmlFormHidden('user_id', get('user_id'));
    htmlFormInput(array('value'=>$user->login,'name'=>'login','style'=>'width:300px;'), lang('users_login'));
    htmlFormInput(array('value'=>$user->firstname,'name'=>'firstname','style'=>'width:300px;'), lang('users_firstname'));
    htmlFormInput(array('value'=>$user->lastname,'name'=>'lastname','style'=>'width:300px;'), lang('users_lastname'));
    htmlFormInput(array('value'=>$user->email,'name'=>'email','style'=>'width:300px;'), lang('users_email'));    
    htmlFormInput(array('value'=>$user->twitter,'name'=>'twitter','style'=>'width:300px;'), lang('users_twitter'));
    htmlFormInput(array('value'=>$user->skype,'name'=>'skype','style'=>'width:300px;'), lang('users_skype'));
    htmlFormInput(array('value'=>$user->icq,'name'=>'icq','style'=>'width:300px;'), lang('users_icq'));
    htmlFormInput(array('value'=>$user->vkontakte,'name'=>'vkontakte','style'=>'width:300px;'), lang('users_vkontakte'));
    htmlFormInput(array('value'=>$user->facebook,'name'=>'facebook','style'=>'width:300px;'), lang('users_facebook'));
    htmlSelect(array('admin'=>lang('users_role_admin'),'user'=>lang('users_role_user')), array('name'=>'role','value'=>'','style'=>'width:200px;'), lang('users_role'), $user[0]->role);
    htmlBr(2);
    htmlFormClose(true, array('name'=>'edit_profile','value'=>lang('users_save')));
?>
</div>

<div style="float:left; margin-left: 60px;">
<?php
    htmlFormOpen('');
    htmlFormHidden('user_id', get('user_id'));
    htmlFormHidden('real_old_password', $user->password);
    htmlFormInput(array('type'=>'password','name'=>'old_password','style'=>'width:300px;'), lang('users_old_password'));
    htmlFormInput(array('type'=>'password','name'=>'new_password','style'=>'width:300px;'), lang('users_new_password'));
    htmlBr(2);
    htmlFormClose(true, array('name'=>'edit_profile_password','value'=>lang('users_save')));
?>
</div>
<div style="clear:both"></div>
<?php
    } else {
        echo '<div class="message-error">'.lang('users_does_not_exist').'</div>';
    }
?>
<!-- /Users_edit -->