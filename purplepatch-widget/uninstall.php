<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('image_optimizer_max_width');
delete_option('image_optimizer_max_height');
delete_option('image_optimizer_compression_quality');
delete_option('optimized_image_count');
delete_option('space_saved');
delete_option('auto_resize_and_optimize');
delete_option('pps_csp_security_enabled');
// Social
delete_option('pps_linkedin_url');
delete_option('pps_twitter_url');
delete_option('pps_facebook_url');
delete_option('pps_youtube_url');
delete_option('pps_tiktok_url');
delete_option('pps_instagram_url');