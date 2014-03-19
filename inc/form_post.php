<?php

	$user_id = $_POST['user_id'];
	$menu	 = $_POST['menu'];
	$submenu = $_POST['sub_menu'];

	set_menu_obfuscation($user_id, $menu, $submenu);

	echo '<div class="message">Settings saved!<br/><br/><a href="/wp-admin/options-general.php?page=sw-menuobfuscator">Return to previous page</a></div>';