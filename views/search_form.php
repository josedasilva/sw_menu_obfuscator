<div class="obfuscator_form">
<div class="logo">
    <img src="<?php echo plugins_url( '../assets/icon-128x128.png', __FILE__ );?>"/>
</div>
<form class="form" method='GET' action='/wp-admin/options-general.php' class=" noprint">
    <input type='hidden' value='sw-menuobfuscator' name='page'/>
    <label>Manage menus for user: </label>
    <select name="user_id">
    <option value="">Please select a user</option>
        <?php 
        $users = get_users();
        foreach($users as $user):
        ?>
    <option value="<?php echo $user->data->ID;?>" <?php echo ($selected_user == $user->data->ID ? 'selected':'');?>><?php echo $user->data->user_login;?></option>
        <?php endforeach; ?>
        ?>
    </select>
        <input type="submit" name="filter" value="<?php _e('Filter') ?>" class="button-primary" />  

</form>

<div class="clearall"></div>

</div>