<?php

function set_menu_obfuscation($user_id, $menu, $submenu) {
	global $wpdb,$swmo_table_name;
	
	$store = array("menu"=>$menu,"submenu"=>$submenu);
	$store_json = json_encode($store);

	$wpdb->query( $wpdb->prepare(
    "REPLACE INTO $swmo_table_name (user_id,menu_data) VALUES ( %s, %s )",
    array(
        $user_id,
        $store_json
    )
	)
	);

}

function get_menu_obfuscation_settings($user_id) {
	global $wpdb,$swmo_table_name;

	$menu_data = $wpdb->get_var( 
		$wpdb->prepare( "SELECT menu_data from $swmo_table_name WHERE user_id=%d",
			array(
				$user_id
			)
		)
	);

	if( !is_null($menu_data))
		return json_decode($menu_data,1);

	return $menu_data;
}


function is_menu_obfuscated($menu, $slug) {
	return is_array($menu) && in_array($slug, $menu);
}

function is_submenu_obfuscated($submenu, $slug) {
	return is_array($submenu) && in_array($slug, $submenu);
}