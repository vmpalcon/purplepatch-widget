<div class="wrap">
    <h1>PPS Settings</h1>

    <!-- Tabs -->
    <h2 class="nav-tab-wrapper">
        <a href="?page=image-optimizer&tab=optimization"
            class="nav-tab <?php echo !isset($_GET['tab']) || $_GET['tab'] == 'optimization' ? 'nav-tab-active' : ''; ?>">
            Image Optimization
        </a>
        <a href="?page=image-optimizer&tab=report"
            class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'report' ? 'nav-tab-active' : ''; ?>">
            Image Optimization Reports
        </a>
    </h2>

    <!-- Tab Content -->
    <div class="tab-content">
        <?php
        if (!isset($_GET['tab']) || $_GET['tab'] == 'optimization') {
            ?>
            <form method="post" action="admin-post.php">
                <?php wp_nonce_field('save_image_optimizer_settings'); ?>
                <input type="hidden" name="action" value="save_image_optimizer_settings">
                <?php
                // Handle form submissions and display messages
                if (isset($_GET['status']) && $_GET['status'] === 'updated') {
                    echo '<div class="updated"><p>Settings saved.</p></div>';
                }
                ?>
                <h2>Image Optimizer Settings</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">Max Width</th>
                        <td><input type="number" name="image_optimizer_max_width"
                                value="<?php echo esc_attr(get_option('image_optimizer_max_width', 1200)); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Max Height</th>
                        <td><input type="number" name="image_optimizer_max_height"
                                value="<?php echo esc_attr(get_option('image_optimizer_max_height', 660)); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Compression Quality</th>
                        <td><input type="number" name="image_optimizer_compression_quality"
                                value="<?php echo esc_attr(get_option('image_optimizer_compression_quality', 80)); ?>"
                                min="0" max="100" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Enable Automatic Image Optimization</th>
                        <td><input type="checkbox" name="auto_resize_and_optimize" value="1" <?php checked(get_option('auto_resize_and_optimize', '0'), '1'); ?> /> Enable</td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save Changes">
                </p>
            </form>
            <?php
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'report') {
            ?>
            <h2>Image Optimization Reports</h2>
            <?php
            $total_images = wp_count_posts('attachment')->inherit;
            $optimized_images_count = get_option('optimized_image_count', 0);
            $space_saved = get_option('space_saved', 0);

            if (isset($_GET['status']) && $_GET['status'] === 'optimized') {
                echo '<div class="updated"><p>All images have been optimized successfully.</p></div>';
            } elseif (isset($_GET['status']) && $_GET['status'] === 'error') {
                echo '<div class="error"><p>There was an error optimizing images. Please try again.</p></div>';
            }

            if ($optimized_images_count > 0) {
                echo '<p>Total Images: ' . esc_html($total_images) . '</p>';
                echo '<p>Total Images Optimized: ' . esc_html($optimized_images_count) . '</p>';
                echo '<p>Total Space Saved: ' . esc_html(number_format($space_saved, 2)) . ' MB</p>';
            } else {
                echo '<p>No images have been optimized yet.</p>';
            }
            ?>
            <form method="post" action="admin-post.php">
                <?php wp_nonce_field('optimize_images'); ?>
                <input type="hidden" name="action" value="optimize_images">
                <p class="submit">
                    <input type="submit" class="button-primary" value="Optimize All Images">
                </p>
            </form>
            <?php
        }
        ?>
    </div>
</div>