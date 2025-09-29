<?php
/**
 * EasyLytics Admin - Appearance Tab
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<h2><?php _e('General Settings', 'easylytics'); ?></h2>

<table class="form-table">
    <tr>
        <th scope="row">
            <label for="eslt_ga4_id"><?php _e('Google Analytics 4 Measurement ID', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_ga4_id" name="eslt_ga4_id" 
                   value="<?php echo esc_attr($ga4_id); ?>" 
                   placeholder="G-XXXXXXXXXX" class="regular-text" />
            <p class="description">
                <?php _e('Enter your GA4 Measurement ID (starts with G-)', 'easylytics'); ?>
            </p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_popup_position"><?php _e('Popup Position', 'easylytics'); ?></label>
        </th>
        <td>
            <select id="eslt_popup_position" name="eslt_popup_position">
                <option value="bottom-right" <?php selected($popup_position, 'bottom-right'); ?>>
                    <?php _e('Bottom Right', 'easylytics'); ?>
                </option>
                <option value="bottom-left" <?php selected($popup_position, 'bottom-left'); ?>>
                    <?php _e('Bottom Left', 'easylytics'); ?>
                </option>
                <option value="bottom-center" <?php selected($popup_position, 'bottom-center'); ?>>
                    <?php _e('Bottom Center', 'easylytics'); ?>
                </option>
            </select>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_disable_youtube_cookies"><?php _e('Disable YouTube Cookies', 'easylytics'); ?></label>
        </th>
        <td>
            <label>
                <input type="checkbox" id="eslt_disable_youtube_cookies" name="eslt_disable_youtube_cookies" 
                    value="1" <?php checked($disable_youtube_cookies, '1'); ?> />
                <?php _e('Disable play Youtube video without prior Youtube cookies consent', 'easylytics'); ?>
            </label>
            <p class="description">
                <?php _e('Automatically disables all YouTube embeds.', 'easylytics'); ?>
                <br>
                <strong><?php _e('Note:', 'easylytics'); ?></strong> <?php _e('This affects both WordPress auto-embeds and manual iframe embeds.', 'easylytics'); ?>
            </p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_cookies_info_url"><?php _e('Cookie Information URL', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="url" id="eslt_cookies_info_url" name="eslt_cookies_info_url" 
                   value="<?php echo esc_attr($cookies_info_url); ?>" 
                   placeholder="https://yoursite.com/cookie-policy" class="regular-text" />
            <p class="description">
                <?php _e('Optional URL to a page with detailed cookie information. Leave empty to hide the link.', 'easylytics'); ?>
            </p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_cookies_info_label"><?php _e('Cookie Information Link Label', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_cookies_info_label" name="eslt_cookies_info_label" 
                   value="<?php echo esc_attr($cookies_info_label); ?>" 
                   class="regular-text" />
            <p class="description">
                <?php _e('Text for the cookie information link (only shown if URL is provided).', 'easylytics'); ?>
            </p>
        </td>
    </tr>
</table>