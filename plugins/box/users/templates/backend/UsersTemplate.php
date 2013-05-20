<!-- Users_add_new -->
<div id="section-bar">
    <?php htmlButton(lang('users_add'),'index.php?id=system&sub_id=users&action=add'); ?>
    <?php runHook('admin_users_extra_buttons'); ?>
</div>
<!-- /Users_add_new -->

<div style="clear:both"></div>

<!-- Users_list -->
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('users_login'); ?></td><td align="left"><?php echo lang('users_email'); ?></td><td><?php echo lang('users_date_registered'); ?></td><td><?php echo lang('users_roles'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php        
        foreach ($users as $user) {
    ?>
     <tr class="admin-table-tr">
        <td class="admin-table-field">
            <?php echo toText($user->login); ?>
        </td>
        <td class="admin-table-field">
            <?php echo toText($user->email); ?>
        </td>
        <td class="admin-table-field">
            <?php echo dateFormat($user->date_registered); ?>
        </td>
        <td class="admin-table-field">
            <?php echo $roles["$user->role"]; ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('users_edit'), 'index.php?id=system&sub_id=users&action=edit&user_id='.$user['id']); ?>
            <?php htmlButtonDelete(lang('users_delete'), 'index.php?id=system&sub_id=users&action=delete&user_id='.$user['id']); ?>
        </td>
     </tr>
    <?php        
        }
    ?>
    </tbody>
</table>
<!-- /Users_list -->