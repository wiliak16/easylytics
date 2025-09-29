<?php
/**
 * EasyLytics Admin - Content Tab
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<h2><?php _e('Content Settings', 'easylytics'); ?></h2>
<p><?php _e('Customize all text that appears in the cookie consent popup. These texts will be used instead of translations.', 'easylytics'); ?></p>

<table class="form-table">
    <tr>
        <th scope="row">
            <label for="eslt_popup_title"><?php _e('Popup Title', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_popup_title" name="eslt_popup_title" 
                   value="<?php echo esc_attr($text_settings['eslt_popup_title']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_popup_description"><?php _e('Popup Description', 'easylytics'); ?></label>
        </th>
        <td>
            <textarea id="eslt_popup_description" name="eslt_popup_description" 
                      rows="3" class="large-text"><?php echo esc_html($text_settings['eslt_popup_description']); ?></textarea>
            <p class="description">
                <?php _e('General description of cookie usage that appears in the popup.', 'easylytics'); ?>
                <br>
                <strong><?php _e('HTML Support:', 'easylytics'); ?></strong> <?php _e('You can use basic HTML tags like &lt;br&gt;, &lt;strong&gt;, &lt;em&gt;, and &lt;a&gt;.', 'easylytics'); ?>
            </p>
            
            <?php if (!empty($text_settings['eslt_popup_description'])): ?>
            <div style="margin-top: 10px; padding: 10px; background: #f0f0f1; border: 1px solid #c3c4c7; border-radius: 4px;">
                <strong><?php _e('Preview:', 'easylytics'); ?></strong><br>
                <div style="margin-top: 5px; font-style: italic;">
                    <?php echo wp_kses($text_settings['eslt_popup_description'], array(
                        'br' => array(),
                        'strong' => array(),
                        'b' => array(),
                        'em' => array(),
                        'i' => array(),
                        'u' => array(),
                        'span' => array('class' => array(), 'style' => array()),
                        'a' => array('href' => array(), 'target' => array(), 'rel' => array())
                    )); ?>
                </div>
            </div>
            <?php endif; ?>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_accept_all_btn"><?php _e('Accept All Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_accept_all_btn" name="eslt_accept_all_btn" 
                   value="<?php echo esc_attr($text_settings['eslt_accept_all_btn']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_reject_all_btn"><?php _e('Reject All Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_reject_all_btn" name="eslt_reject_all_btn" 
                   value="<?php echo esc_attr($text_settings['eslt_reject_all_btn']); ?>" 
                   class="regular-text" />
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_cookie_settings_btn"><?php _e('Cookie Settings Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_cookie_settings_btn" name="eslt_cookie_settings_btn" 
                   value="<?php echo esc_attr($text_settings['eslt_cookie_settings_btn']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_cookie_settings_hide_btn"><?php _e('Hide Cookie Settings Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_cookie_settings_hide_btn" name="eslt_cookie_settings_hide_btn" 
                   value="<?php echo esc_attr($text_settings['eslt_cookie_settings_hide_btn']); ?>" 
                   class="regular-text" />
            <p class="description"><?php _e('Text shown when cookie settings are expanded (toggle mode).', 'easylytics'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_save_preferences_btn"><?php _e('Save Preferences Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_save_preferences_btn" name="eslt_save_preferences_btn" 
                   value="<?php echo esc_attr($text_settings['eslt_save_preferences_btn']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_close_btn_label"><?php _e('Close Button Label', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_close_btn_label" name="eslt_close_btn_label" 
                   value="<?php echo esc_attr($text_settings['eslt_close_btn_label']); ?>" 
                   class="regular-text" />
            <p class="description"><?php _e('Accessibility label for the close button (screen readers).', 'easylytics'); ?></p>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_technical_cookies_title"><?php _e('Technical Cookies Title', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_technical_cookies_title" name="eslt_technical_cookies_title" 
                   value="<?php echo esc_attr($text_settings['eslt_technical_cookies_title']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_technical_cookies_desc"><?php _e('Technical Cookies Description', 'easylytics'); ?></label>
        </th>
        <td>
            <textarea id="eslt_technical_cookies_desc" name="eslt_technical_cookies_desc" 
                      rows="2" class="large-text"><?php echo esc_textarea($text_settings['eslt_technical_cookies_desc']); ?></textarea>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_analytical_cookies_title"><?php _e('Analytical Cookies Title', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_analytical_cookies_title" name="eslt_analytical_cookies_title" 
                   value="<?php echo esc_attr($text_settings['eslt_analytical_cookies_title']); ?>" 
                   class="regular-text" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_analytical_cookies_desc"><?php _e('Analytical Cookies Description', 'easylytics'); ?></label>
        </th>
        <td>
            <textarea id="eslt_analytical_cookies_desc" name="eslt_analytical_cookies_desc" 
                      rows="2" class="large-text"><?php echo esc_textarea($text_settings['eslt_analytical_cookies_desc']); ?></textarea>
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_youtube_cookies_title"><?php _e('YouTube Cookies Title', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_youtube_cookies_title" name="eslt_youtube_cookies_title" 
                   value="<?php echo esc_attr($text_settings['eslt_youtube_cookies_title']); ?>" 
                   class="regular-text" />
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_youtube_cookies_desc"><?php _e('YouTube Cookies Description', 'easylytics'); ?></label>
        </th>
        <td>
            <textarea id="eslt_youtube_cookies_desc" name="eslt_youtube_cookies_desc" 
                      rows="2" class="large-text"><?php echo esc_textarea($text_settings['eslt_youtube_cookies_desc']); ?></textarea>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_youtube_blocked_title"><?php _e('YouTube Blocked Title', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_youtube_blocked_title" name="eslt_youtube_blocked_title" 
                   value="<?php echo esc_attr($text_settings['eslt_youtube_blocked_title']); ?>" 
                   class="regular-text" />
            <p class="description"><?php _e('Title shown when YouTube video is blocked.', 'easylytics'); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_youtube_blocked_message"><?php _e('YouTube Blocked Message', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_youtube_blocked_message" name="eslt_youtube_blocked_message" 
                   value="<?php echo esc_attr($text_settings['eslt_youtube_blocked_message']); ?>" 
                   class="large-text" />
            <p class="description"><?php _e('Message shown when YouTube video is blocked.', 'easylytics'); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_youtube_accept_button"><?php _e('YouTube Accept Button', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_youtube_accept_button" name="eslt_youtube_accept_button" 
                   value="<?php echo esc_attr($text_settings['eslt_youtube_accept_button']); ?>" 
                   class="regular-text" />
            <p class="description"><?php _e('Button text to allow YouTube cookies.', 'easylytics'); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="eslt_success_message"><?php _e('Success Message', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="text" id="eslt_success_message" name="eslt_success_message" 
                   value="<?php echo esc_attr($text_settings['eslt_success_message']); ?>" 
                   class="large-text" />
            <p class="description"><?php _e('Message displayed when cookie preferences are successfully saved.', 'easylytics'); ?></p>
        </td>
    </tr>
</table>

<div style="margin: 20px 0; padding: 15px; background: #f0f6fc; border: 1px solid #c3d4e8; border-radius: 4px;">
    <h4 style="margin-top: 0;"><?php _e('Reset Content to Defaults', 'easylytics'); ?></h4>
    <p><?php _e('Reset all content texts to default English values.', 'easylytics'); ?></p>
    <button type="button" id="reset-texts" class="button button-secondary">
        <?php _e('Reset Content to Defaults', 'easylytics'); ?>
    </button>
</div>