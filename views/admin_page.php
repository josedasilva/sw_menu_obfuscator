

<div class="wrap">
    <div class="card">
        <h2>Menu Obfuscator</h2>


            <?php

            if(!$is_post) :
                include_once(__DIR__."/search_form.php");
            
            ?>
            
            <br/><br/> 

            <?php 
            endif;

            ?>
    </div>
<?php
  

  if( FALSE !== $user_menu_data): 

    
    ?>
<div class="card">    
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
     			<td class='check'><input type='checkbox' value='<?php echo $menu_slug;?>' name='menu[]' class='mainmenu' id="m_<?php echo $menu_idx;?>" <?php echo (Menu_Obfuscator::is_menu_obfuscated($user_menu_data['menu'], $menu_slug) ) ? 'checked' : '';?> /></td>
     		</tr>
<?php			
     			foreach($sub_menu as $submenu_item) {
     				$submenu_slug = $submenu_item[2];
     				$submenu_text = preg_replace("/<span.+?>.+?<\/span>/i", "", $submenu_item[0]);
     ?>
          	<tr>
          			<td></td>
          			<td><?php echo stripslashes($submenu_text);?></td>
          			<td class='check'><input type='checkbox' value='<?php echo $submenu_slug;?>' name='sub_menu[<?php echo $menu_slug;?>][]' class='submenu m_<?php echo $menu_idx;?>' <?php echo (Menu_Obfuscator::is_submenu_obfuscated($user_menu_data['submenu'][$menu_slug], $submenu_slug) ) ? 'checked' : '';?>/></td>
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
</div>
<?php endif; ?>
</div>