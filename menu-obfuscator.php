<?php
/*
    Plugin Name: Menu Obfuscator
    Plugin URI: https://wordpress.org/plugins/menu-obfuscator/
    Description: A plugin to obfuscate menus based on user 
    Version: 0.9
    Author: Jose da Silva
    Author URI: http://blog.josedasilva.net
    License: GPL2
*/
 
define("SWMO_PATH", WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)));

class Menu_Obfuscator { 
    private $database_table;

    public function __construct() {
        $this->database_table = $wpdb->prefix.'sw_menuobfuscator';
    }

    /** Initialize actions and hooks */
    public function init() {
       
        $this->init_actions();
        $this->init_hooks();
    }
    
    private function init_hooks() {
        register_activation_hook( __FILE__, array($this,'run_on_activation') );
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('swmo_js', SWMO_PATH . 'assets/js/swmo.js?v1.0', array('jquery'));
    }

    public function enqueue_styles() {
        wp_enqueue_style('swmo_css', SWMO_PATH . 'assets/css/swmo.css');
    }

    private function init_actions() {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_head', array($this, 'enqueue_scripts'), 120);
        add_action('admin_head', array($this, 'enqueue_styles'), 120);
        add_action('admin_menu', array($this,'define_admin_menu'));
        add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this,'show_options_link' ));
    }

    public function show_options_link( $links ) {
        $links = array_merge( array(
            '<a href="' . esc_url( admin_url( '/options-general.php?page=sw-menuobfuscator' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
        ), $links );
        return $links;
    }
    
    public function define_admin_menu() {
        add_options_page( "Menu Obfuscator", "Menu Obfuscator", "manage_options", 'sw-menuobfuscator', array($this, 'render_admin_page'));
    }

    /**
     * Renders the amdin page
     */
    public function render_admin_page() {
        global $wp_admin_bar, $current_user, $menu, $submenu;

        $is_post = false;
        $show_message = false;
        $selected_user = isset($_GET['user_id']) ? intval($_GET['user_id']) : false;

        /**
         * If we have a POST just save the data on database and show success page
         */
        if(isset($_POST['user_id'])) {
            $is_post = true;
            $this->save_options(); // Save the options
            $show_message   =   array("title" => __( "Settings saved!" ));
            include_once __DIR__."/views/admin_success_page.php";
           
        } else {

            // Looking into user selection datagrid
            $user_menu_data = false;
            if( !$is_post && $selected_user ) {
                $user_menu_data = $this->get_menu_obfuscation_settings($selected_user);
            }

            // render the admin page to manage
            include_once __DIR__."/views/admin_page.php";
        }
    }

    /**
     * Hndling the POST action
     */
    private function save_options() {
        // Save on database
        $user_id = $_POST['user_id'];
        $menu    = $_POST['menu'];
        $submenu = $_POST['sub_menu'];
	
        $this->set_menu_obfuscation($user_id, $menu, $submenu);

    }

    /**
     * Saving the user selection on the database field
     */
    private function set_menu_obfuscation($user_id, $menu, $submenu) {
        global $wpdb;
        $store = array("menu"=>$menu,"submenu"=>$submenu);
        $store_json = json_encode($store);
    
        $wpdb->query( 
            $wpdb->prepare(
                "REPLACE INTO {$this->database_table} (user_id,menu_data) VALUES ( %s, %s )",
                array(
                    $user_id,
                    $store_json
                )
            )
        );
    
    }

    /**
     * Load the data from database
     */
    private function get_menu_obfuscation_settings($user_id) {
        global $wpdb;
        $menu_data = $wpdb->get_var( 
            $wpdb->prepare( "SELECT menu_data from {$this->database_table} WHERE user_id=%d",
                array(
                    $user_id
                )
            )
        );
    
        if( !is_null($menu_data))
            return json_decode($menu_data,1);
    
        return $menu_data;
    }

    /**
     * Verify if a menu is defined as no show
     */
    public static function is_menu_obfuscated($menu, $slug) {
       
        return is_array($menu) && in_array($slug, $menu);
    }
    
    public static function is_submenu_obfuscated($submenu, $slug) {
        return is_array($submenu) && in_array($slug, $submenu);
    }


    public function run_on_activation() {
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->database_table} (
            `user_id` int(11) NOT NULL,
            `menu_data` text COLLATE utf8_swedish_ci NOT NULL,
            PRIMARY KEY (`user_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci";
      
          require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
          dbDelta( $sql );
        
    }
}

$menu_obfuscator_plugin = new Menu_Obfuscator();
$menu_obfuscator_plugin->init();
