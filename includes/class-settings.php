<?php
class PPW_Settings
{
    public function __construct()
    {
        // Register settings and options
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings()
    {
        register_setting('ppw_settings_group', 'image_optimizer_max_width');
        register_setting('ppw_settings_group', 'image_optimizer_max_height');
        register_setting('ppw_settings_group', 'image_optimizer_compression_quality');
        register_setting('ppw_settings_group', 'auto_resize_and_optimize');
    }
}
