<?php
class PPW_Other_Feature
{
    public function __construct()
    {
        add_action('customize_register', array($this, 'pps_social_media_customizer'));
        add_action('admin_post_pps_save_social_media_settings', array($this, 'pps_save_social_media_settings'));

        // Register the security settings
        add_action('admin_init', array($this, 'pps_csp_register_settings'));
        add_action('admin_post_pps_save_security_settings', array($this, 'pps_save_security_settings'));

        // Apply security headers
        add_action('send_headers', array($this, 'pps_add_security_headers'));
    }

    // Register the security settings
    public function pps_csp_register_settings()
    {
        register_setting('pps_csp_settings_group', 'pps_csp_security_enabled');
    }

    // Apply security headers
    public function pps_add_security_headers()
    {
        $is_security_enabled = get_option('pps_csp_security_enabled', 'disabled');
        if ($is_security_enabled === 'enabled') {
            header("X-Frame-Options: SAMEORIGIN");
            header("X-Content-Type-Options: nosniff");
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
        }
    }

    // Handle form submission for security settings
    public function pps_save_security_settings()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user');
        }

        // Verify nonce for security
        check_admin_referer('pps_csp_settings_group-options', 'pps_csp_settings_group_nonce');

        // Save the security setting
        if (isset($_POST['pps_csp_security_enabled'])) {
            update_option('pps_csp_security_enabled', sanitize_text_field(wp_unslash($_POST['pps_csp_security_enabled'])));
        }

        // Redirect back to the Security tab with status=updated
        wp_redirect(add_query_arg(array(
            'page' => 'image-optimizer', // Make sure this slug matches your page
            'tab' => 'security',
            'status' => 'updated'
        ), admin_url('admin.php')));
        exit;
    }

    public function pps_social_media_customizer($wp_customize)
    {
        // Add Social Media Links section
        $wp_customize->add_section('pps_social_media_section', array(
            'title' => __('Social Media Links', 'pps'),
            'priority' => 30,
            'description' => 'Add your social media links here',
        ));

        // Define Social Media Platforms
        $social_media = [
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'instagram' => 'Instagram',
        ];

        // Loop through social media platforms and create settings & controls
        foreach ($social_media as $key => $label) {
            // Add Setting for each social media URL
            $wp_customize->add_setting("pps_{$key}_url", array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        
            // Add Control for each social media URL
            $wp_customize->add_control("pps_{$key}_url", array(
                 // Translators: %s is the social media platform name, e.g., 'Twitter'
                'label' => sprintf(__('Social Media Platform URL: %s', 'pps'), $label),
                'section' => 'pps_social_media_section',
                'settings' => "pps_{$key}_url",
                'type' => 'url',
            ));
        
            // Save the customizer settings into WordPress options after saving customizer
            add_action('customize_save_after', function () use ($key) {
                $option_value = get_theme_mod("pps_{$key}_url");
                update_option("pps_{$key}_url", $option_value);
            });
        }
    }

    public function pps_save_social_media_settings()
    {
        // Verify the nonce for security
        check_admin_referer('pps_save_social_media_settings');

        // Define Social Media Platforms
        $social_media = [
            'linkedin' => 'pps_linkedin_url',
            'twitter' => 'pps_twitter_url',
            'facebook' => 'pps_facebook_url',
            'youtube' => 'pps_youtube_url',
            'tiktok' => 'pps_tiktok_url',
            'instagram' => 'pps_instagram_url',
        ];

        // Update options with the submitted values
        foreach ($social_media as $key => $option_name) {
            if (isset($_POST[$option_name])) {
                // Update the option value
                update_option($option_name, sanitize_text_field(wp_unslash($_POST[$option_name])));

                // Sync the Customizer setting with the updated option value
                set_theme_mod("pps_{$key}_url", sanitize_text_field(wp_unslash($_POST[$option_name])));
            }
        }

        // Redirect with a success status message
        wp_redirect(add_query_arg(array('tab' => 'social_media', 'status' => 'updated'), wp_get_referer()));
        exit;
    }
}