<?php
class PPW_Admin_Menu
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_menu_page'));
    }

    public function add_menu_page()
    {
        add_menu_page(
            'PPS Settings',
            'PPS Settings',
            'manage_options',
            'image-optimizer',
            array($this, 'settings_page')
        );
    }

    public function settings_page()
    {
        // Include the settings page view here
        include(plugin_dir_path(__FILE__) . '../views/settings-page.php');
    }
}
