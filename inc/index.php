<?php
  require_once dirname(__FILE__)."/functions.php";

?>
<div class="wrap">
<h2>Menu Obfuscator</h2>

<?php

  // Handling for saving (POST)
  $is_post = false;
  if(isset($_POST['user_id'])) {
    include_once dirname(__FILE__).'/form_post.php';
    $is_post = true;
  }


  // Check if we have selected a filter
  $selected_user = isset($_GET['user_id']) ? intval($_GET['user_id']) : false;
  if(!$is_post) :
?>
<form method='GET' action='/wp-admin/options-general.php' class=" noprint">
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
<br/><br/> 

<?php 
  endif;

  
  if($selected_user && !$is_post): 

    $menu_data = get_menu_obfuscation_settings($selected_user);
    ?>
<form method='POST' action='/wp-admin/options-general.php?page=sw-menuobfuscator' class=" noprint">
<input type='hidden' name='user_id' value='<?php echo $selected_user;?>'/>
<table class="widefat">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Submenu</th>
                <th class='check'>Hidden</th>
            </tr>

        </thead>
        <tbody>
	
<?php

	foreach($menu as $menu_item) {
		if( !empty($menu_item[0]) ) {
			$menu_slug = $menu_item[2];
			$sub_menu = isset($submenu[$menu_slug]) ? $submenu[$menu_slug] : array();
			$menu_text =preg_replace("/<span.+?>.+?<\/span>/i", "", $menu_item[0]);
			$menu_idx = md5($menu_slug);


?>
     		<tr>
     			<td colspan='2'><?php echo stripslashes($menu_text);?></td>
     			<td class='check'><input type='checkbox' value='<?php echo $menu_slug;?>' name='menu[]' class='mainmenu' id="m_<?php echo $menu_idx;?>" <?php echo (is_menu_obfuscated($menu_data['menu'], $menu_slug) ) ? 'checked' : '';?> /></td>
     		</tr>
<?php			
     			foreach($sub_menu as $submenu_item) {
     				$submenu_slug = $submenu_item[2];
     				$submenu_text = preg_replace("/<span.+?>.+?<\/span>/i", "", $submenu_item[0]);
     ?>
          	<tr>
          			<td></td>
          			<td><?php echo stripslashes($submenu_text);?></td>
          			<td class='check'><input type='checkbox' value='<?php echo $submenu_slug;?>' name='sub_menu[<?php echo $menu_slug;?>][]' class='submenu m_<?php echo $menu_idx;?>' <?php echo (is_submenu_obfuscated($menu_data['submenu'][$menu_slug], $submenu_slug) ) ? 'checked' : '';?>/></td>
          		</tr>
     <?php				
          				
          			}

		}
	}
?>
<tr>
                <td></td>
                <td></td>
                <td class='check'><input type="submit" name="save" value="<?php _e('Save Menus') ?>" class="button-primary" />  
</td>
              </tr>
</tbody>
</table>

</form>

<?php endif; ?>
</div>