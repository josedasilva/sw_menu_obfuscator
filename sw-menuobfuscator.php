<?php
   /*
   Plugin Name: Menu Obfuscator
   Plugin URI: http://seedworks.pt/wp-plugins/sw-menuobfuscator.zip
   Description: A plugin to obfuscate menus based on user loggged
   Version: 0.3
   Author: Jose da Silva
   Author URI: http://blog.josedasilva.net
   License: GPL2
   */

define(SWMO_PATH, WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)));
global $swmo_table_name, $wpdb;

$swmo_table_name = $wpdb->prefix.'sw_menuobfuscator';

require_once dirname(__FILE__)."/inc/functions.php";


function remove_admin_menu_sidebar_items() {

	global $wp_admin_bar, $current_user;

	$obs = get_menu_obfuscation_settings($current_user->ID);

	if( is_array($obs) ) {

		foreach((array)$obs['menu'] as $menu) {
			remove_menu_page($menu); 
		}


		if(is_array($obs['submenu'])) {
			foreach((array)$obs['submenu'] as $menu=>$submenu_items) {
			
				foreach($submenu_items as $submenu) {

					remove_submenu_page($menu,$submenu); 
				}
			}
		}

	}

}

function swmo_init() {
        wp_enqueue_script('jquery');
        remove_admin_menu_sidebar_items();
}


function swmo_head() {
    wp_enqueue_script('swmo_js', SWMO_PATH . 'js/swmo.js?v1.0', array('jquery'));
    wp_enqueue_style('swmo_css', SWMO_PATH . 'css/swmo.css');
}

add_action('admin_init', 'swmo_init');
add_action('admin_head', 'swmo_head', 120);
add_action('admin_menu', 'add_items_to_menu');
register_activation_hook( __FILE__, 'sw_mo_install' );

// Adding settings link
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

// Add settings link on plugin page
function your_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=sw-menuobfuscator">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

function add_items_to_menu() {
	add_options_page( "Menu Obfuscator", "Menu Obfuscator", "manage_options", 'sw-menuobfuscator', 'sw_menuobfuscator'); 
}

function  sw_menuobfuscator () {
	global $wp_admin_bar, $current_user, $menu, $submenu;
	include dirname(__FILE__)."/inc/index.php";
}

function sw_mo_install() {
   global $wpdb,$swmo_table_name;
   require_once dirname(__FILE__)."/inc/install.php";
}

?>
