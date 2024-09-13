<?php
/*
Plugin Name: PurplePatch Widget
Description: A plugin for image optimization and other functionalities provided by Purplepatchservices.
Version: 1.0
Author: PurplePatch Services
Author URI: https://www.purplepatchservices.com/
License: GPL2
*/

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-image-optimizer.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-settings.php';

// Initialize the plugin
function ppw_init()
{
    $admin_menu = new PPW_Admin_Menu();
    $image_optimizer = new PPW_Image_Optimizer();
    $settings = new PPW_Settings();
}
add_action('plugins_loaded', 'ppw_init');

// Register uninstall hook to clean up options
register_uninstall_hook(__FILE__, 'ppw_uninstall');

function ppw_uninstall()
{
    delete_option('optimized_image_count');
    delete_option('space_saved');
    delete_option('auto_resize_and_optimize');
}