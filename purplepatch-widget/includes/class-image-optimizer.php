<?php
class PPW_Image_Optimizer
{
    public function __construct()
    {
        add_action('admin_post_optimize_images', array($this, 'handle_image_optimization'));
        add_action('admin_post_save_image_optimizer_settings', array($this, 'handle_settings_save'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_styles'));

        // Register the automatic image optimization if enabled
        if ('1' === get_option('auto_resize_and_optimize', '0')) {
            add_action('add_attachment', array($this, 'auto_resize_and_optimize_images'));
        }
    }

    public function handle_settings_save()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        // Verify nonce for security
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_image_optimizer_settings')) {
            wp_die('Security check failed.');
        }

        // Save settings
        update_option('image_optimizer_max_width', sanitize_text_field($_POST['image_optimizer_max_width']));
        update_option('image_optimizer_max_height', sanitize_text_field($_POST['image_optimizer_max_height']));
        update_option('image_optimizer_compression_quality', sanitize_text_field($_POST['image_optimizer_compression_quality']));
        update_option('auto_resize_and_optimize', isset($_POST['auto_resize_and_optimize']) ? '1' : '0');

        wp_redirect(add_query_arg(array('page' => 'image-optimizer', 'tab' => 'optimization', 'status' => 'updated'), admin_url('admin.php')));
        exit;
    }

    public function handle_image_optimization()
    {
        if (!current_user_can('manage_options')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        // Verify nonce for security
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'optimize_images')) {
            wp_die('Security check failed.');
        }

        $max_width = get_option('image_optimizer_max_width', 1200);
        $max_height = get_option('image_optimizer_max_height', 660);
        $compression_quality = get_option('image_optimizer_compression_quality', 80);

        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'post_status' => 'any',
        );
        $attachments = get_posts($args);

        $optimized_images_count = 0;
        $original_size_total = 0;
        $optimized_size_total = 0;

        if ($attachments) {
            foreach ($attachments as $attachment) {
                $attachment_id = $attachment->ID;
                $image_path = get_attached_file($attachment_id);
                $file_type = wp_check_filetype($image_path);

                if (in_array($file_type['type'], array('image/jpeg', 'image/png', 'image/gif', 'image/webp'))) {
                    $original_size = filesize($image_path);

                    $image_editor = wp_get_image_editor($image_path);
                    if (!is_wp_error($image_editor)) {
                        $image_editor->resize($max_width, $max_height, false);

                        if ($file_type['type'] === 'image/jpeg' || $file_type['type'] === 'image/webp') {
                            $image_editor->set_quality($compression_quality);
                        }

                        $saved = $image_editor->save($image_path);
                        if (!is_wp_error($saved)) {
                            $optimized_size = isset($saved['size']) ? $saved['size'] : filesize($image_path);

                            $optimized_images_count++;
                            $original_size_total += $original_size;
                            $optimized_size_total += $optimized_size;

                            $resized_image_data = wp_generate_attachment_metadata($attachment_id, $image_path);
                            wp_update_attachment_metadata($attachment_id, $resized_image_data);
                        }
                    }
                }
            }

            update_option('optimized_image_count', $optimized_images_count);

            $space_saved = ($original_size_total - $optimized_size_total) / (1024 * 1024);
            update_option('space_saved', $space_saved);

            wp_redirect(add_query_arg(array('page' => 'image-optimizer', 'tab' => 'report', 'status' => 'optimized'), admin_url('admin.php')));
            exit;
        } else {
            wp_redirect(add_query_arg(array('page' => 'image-optimizer', 'tab' => 'report', 'status' => 'error'), admin_url('admin.php')));
            exit;
        }
    }



    public function auto_resize_and_optimize_images($attachment_id)
    {
        // Auto-resize and optimize images upon upload
        if ('1' !== get_option('auto_resize_and_optimize', '0')) {
            return; // Skip processing if not enabled
        }

        $image_path = get_attached_file($attachment_id);
        $file_type = wp_check_filetype($image_path);

        if (in_array($file_type['type'], array('image/jpeg', 'image/png', 'image/gif', 'image/webp'))) {
            $max_width = get_option('image_optimizer_max_width', 1200);
            $max_height = get_option('image_optimizer_max_height', 660);
            $compression_quality = get_option('image_optimizer_compression_quality', 80);

            $original_size = filesize($image_path);

            $image_editor = wp_get_image_editor($image_path);
            if (!is_wp_error($image_editor)) {
                $image_editor->resize($max_width, $max_height, false);

                if ($file_type['type'] === 'image/jpeg' || $file_type['type'] === 'image/webp') {
                    $image_editor->set_quality($compression_quality);
                }

                $saved = $image_editor->save($image_path);
                if (!is_wp_error($saved)) {
                    $optimized_size = isset($saved['size']) ? $saved['size'] : filesize($image_path);

                    $resized_image_data = wp_generate_attachment_metadata($attachment_id, $image_path);
                    wp_update_attachment_metadata($attachment_id, $resized_image_data);

                    // Update report data
                    $optimized_images = get_option('optimized_image_count', 0) + 1;
                    update_option('optimized_image_count', $optimized_images);

                    $space_saved = get_option('space_saved', 0);
                    $space_saved += ($original_size - $optimized_size) / (1024 * 1024);
                    update_option('space_saved', $space_saved);
                }
            }
        }
    }

    public function enqueue_admin_scripts_styles($hook)
    {
        // Only load scripts and styles on plugin admin pages
        if ($hook !== 'settings_page_image-optimizer') {
            return;
        }

        // Enqueue admin stylesheet
        wp_enqueue_style('ppw-admin-style', plugin_dir_url(__FILE__) . 'admin-style.css');

        // Enqueue admin script
        wp_enqueue_script('ppw-admin-script', plugin_dir_url(__FILE__) . 'admin-script.js', array('jquery'), null, true);
    }
}
