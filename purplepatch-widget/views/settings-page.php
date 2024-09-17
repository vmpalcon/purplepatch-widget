<div class="wrap">
    <h1>PPS Settings</h1>
<?php 
$nonce = wp_create_nonce('image_optimizer_nonce'); 

?>
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
        <a href="?page=image-optimizer&tab=social_media"
            class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'social_media' ? 'nav-tab-active' : ''; ?>">
            Social Media Links
        </a>
        <a href="?page=image-optimizer&tab=security"
            class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'security' ? 'nav-tab-active' : ''; ?>">
            Security
        </a>

    </h2>

    <!-- Tab Content -->
    <div class="tab-content">
        <?php
	
        if (!isset($_GET['tab']) || $_GET['tab'] == 'optimization') {
            ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('pps_save_image_optimizer_settings'); ?>
                <input type="hidden" name="action" value="pps_save_image_optimizer_settings">
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
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'social_media') {
            ?>
            <h2>Social Media Links</h2>
            <?php
            // Display success message
            if (isset($_GET['status']) && $_GET['status'] === 'updated') {
                echo '<div class="updated"><p>Social media settings saved successfully.</p></div>';
            }
            ?>
            <form method="post" action="admin-post.php">
                <?php wp_nonce_field('pps_save_social_media_settings'); ?>
                <input type="hidden" name="action" value="pps_save_social_media_settings">

                <table class="form-table">
                    <?php
                    // Social Media URLs and Icons
                    $social_media = [
                        'linkedin' => 'LinkedIn',
                        'twitter' => 'Twitter',
                        'facebook' => 'Facebook',
                        'youtube' => 'YouTube',
                        'tiktok' => 'TikTok',
                        'instagram' => 'Instagram',
                    ];

                    foreach ($social_media as $key => $label) {
                        // Escape $label before outputting it
                        $escaped_label = esc_html($label);
                        ?>
                        <tr>
                            <th scope="row"><?php echo esc_html($escaped_label . ' URL'); ?></th>
                            <td>
                                <input type="url" name="pps_<?php echo esc_attr($key); ?>_url"
                                       value="<?php echo esc_attr(get_option("pps_{$key}_url")); ?>" />
                            </td>
                        </tr>
                        <?php
                    }
                    
                    
                    ?>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save Changes">
                </p>
            </form>
            <style>
                /* CSS Simple Pre Code */
                pre {
                    background: #333;
                    white-space: pre;
                    word-wrap: break-word;
                    overflow: auto;
                }

                pre.code {
                    margin: 20px 25px;
                    border-radius: 4px;
                    border: 1px solid #292929;
                    position: relative;
                }

                pre.code label {
                    font-family: sans-serif;
                    font-weight: bold;
                    font-size: 13px;
                    color: #ddd;
                    position: absolute;
                    left: 1px;
                    top: 15px;
                    text-align: center;
                    width: 60px;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    pointer-events: none;
                }

                pre.code code {
                    font-family: 'Inconsolata', 'Monaco', 'Consolas', 'Andale Mono',
                        'Bitstream Vera Sans Mono', 'Courier New', Courier, monospace;
                    display: block;
                    margin: 0 0 0 60px;
                    padding: 15px 16px 14px;
                    border-left: 1px solid #555;
                    overflow-x: auto;
                    font-size: 13px;
                    line-height: 19px;
                    color: #ddd;
                }

                pre::after {
                    content: 'double click to selection';
                    padding: 0;
                    width: auto;
                    height: auto;
                    position: absolute;
                    right: 18px;
                    top: 14px;
                    font-size: 12px;
                    color: #ddd;
                    line-height: 20px;
                    overflow: hidden;
                    -webkit-backface-visibility: hidden;
                    transition: all 0.3s ease;
                }

                pre:hover::after {
                    opacity: 0;
                    visibility: visible;
                }

                pre.code-css code {
                    color: #91a7ff;
                }

                pre.code-html code {
                    color: #aed581;
                }

                pre.code-javascript code {
                    color: #ffa726;
                }

                pre.code-jquery code {
                    color: #4dd0e1;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Replace <i> elements with rel="pre" with <pre><code>...</code></pre>
                    document.querySelectorAll('i[rel="pre"]').forEach((element) => {
                        const preCode = document.createElement('pre');
                        const code = document.createElement('code');
                        code.innerHTML = element.innerHTML;
                        preCode.appendChild(code);
                        element.parentNode?.replaceChild(preCode, element);
                    });

                    // Add double-click event listeners to <pre>, <kbd>, and <blockquote> elements
                    const pres = document.querySelectorAll('pre, kbd, blockquote');
                    pres.forEach((element) => {
                        element.addEventListener('dblclick', () => {
                            const selection = window.getSelection();
                            if (selection) {
                                const range = document.createRange();
                                range.selectNodeContents(element);
                                selection.removeAllRanges();
                                selection.addRange(range);
                            }
                        });
                    });
                });
            </script>
            <!-- Usage in Frontend -->
            <h3>Usage in Frontend</h3>
            <p>To display social media icons on the frontend of your site, you can use the following code in your theme's
                template files (e.g., <code>header.php</code>, <code>footer.php</code>):</p>
<pre class='code code-html'>
    <label>PHP</label>
    <code>
        &lt;ul class="social"&gt;
            &lt;?php if (get_option('pps_linkedin_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_linkedin_url')); ?&gt;" target="_blank" aria-label="LinkedIn" rel="noopener"&gt;
                        &lt;i class="fab fa-linkedin-in"&gt;&lt;/i&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (get_option('pps_twitter_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_twitter_url')); ?&gt;" target="_blank" aria-label="Twitter" rel="noopener"&gt;
                        &lt;svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"&gt;
                            &lt;path d="M12.1794 1.5H14.3887L9.56324 7.00625L15.2399 14.5H10.7962L7.31324 9.95625L3.33271 14.5H1.12026L6.28056 8.60938L0.838623 1.5H5.39495L8.53994 5.65312L12.1794 1.5ZM11.4033 13.1812H12.6269L4.7284 2.75H3.41408L11.4033 13.1812Z" fill="currentColor"&gt;&lt;/path&gt;
                        &lt;/svg&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (get_option('pps_facebook_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_facebook_url')); ?&gt;" target="_blank" aria-label="Facebook" rel="noopener"&gt;
                        &lt;i class="fab fa-facebook-f"&gt;&lt;/i&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (get_option('pps_youtube_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_youtube_url')); ?&gt;" target="_blank" aria-label="YouTube" rel="noopener"&gt;
                        &lt;i class="fab fa-youtube"&gt;&lt;/i&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (get_option('pps_tiktok_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_tiktok_url')); ?&gt;" target="_blank" aria-label="TikTok" rel="noopener"&gt;
                        &lt;i class="fab fa-tiktok"&gt;&lt;/i&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (get_option('pps_instagram_url')) : ?&gt;
                &lt;li&gt;
                    &lt;a href="&lt;?php echo esc_url(get_option('pps_instagram_url')); ?&gt;" target="_blank" aria-label="Instagram" rel="noopener"&gt;
                        &lt;i class="fab fa-instagram"&gt;&lt;/i&gt;
                    &lt;/a&gt;
                &lt;/li&gt;
            &lt;?php endif; ?&gt;
        &lt;/ul&gt;
    </code>
</pre>

<p>Make sure to include the necessary CSS to style the icons as follows:</p>

<pre class='code code-css'>
    <label>CSS</label>
    <code>
        ul.social {
            margin: 0;
            padding: 0;
            list-style: none;
            text-align: right;
            white-space: nowrap;
        }
        ul.social li {
            padding: 0;
            margin-right: 5px;
            margin-top: 4px;
            display: inline-block;
        }
        ul.social li a {
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            padding: 7px;
            transition: all 0.3s;
        }
        ul.social li a:hover {
            border-color: #999;
            background-color: #f1f1f1;
        }
        ul.social li a i {
            font-size: 14px;
        }
    </code>
</pre>

            <?php
        } elseif (isset($_GET['tab']) && $_GET['tab'] == 'security') {
            ?>
            <!-- Security Tab Content -->

            <?php
            // Show success message only for the Security tab
            if (isset($_GET['status']) && $_GET['status'] === 'updated') {
                echo '<div class="updated"><p>Security settings saved successfully.</p></div>';
            }
            ?>
            <h1>Custom Security Plugin Settings</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                wp_nonce_field('pps_csp_settings_group-options', 'pps_csp_settings_group_nonce');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Enable Security Headers</th>
                        <td>
                            <select name="pps_csp_security_enabled">
                                <option value="disabled" <?php selected(get_option('pps_csp_security_enabled'), 'disabled'); ?>>
                                    Disabled
                                </option>
                                <option value="enabled" <?php selected(get_option('pps_csp_security_enabled'), 'enabled'); ?>>
                                    Enabled
                                </option>
                            </select>
                            <p>X-Frame-Options: SAMEORIGIN</p>
                            <p>X-Content-Type-Options: nosniff</p>
                            <p>Strict-Transport-Security: max-age=31536000; includeSubDomains</p>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="action" value="pps_save_security_settings">
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save Changes">
                </p>
            </form>


            <?php
        }
			
        ?>
    </div>
</div>