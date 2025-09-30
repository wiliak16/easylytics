<?php
/**
 * EasyLytics Frontend Class
 * 
 * Handles frontend cookie popup display and shortcodes
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EasyLytics_Frontend {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Constructor can be used for initialization if needed
    }
    
    /**
     * Render cookie consent popup
     */
    public function render_popup() {
        $position = get_option('eslt_popup_position', 'bottom-right');
        
        // Get custom text settings
        $popup_title = get_option('eslt_popup_title', 'We use cookies');
        $popup_description = get_option('eslt_popup_description', 'We use cookies to improve your experience on our website and to analyze traffic. Choose which cookies you want to allow.');
        $accept_all_btn = get_option('eslt_accept_all_btn', 'Accept All');
        $reject_all_btn = get_option('eslt_reject_all_btn', 'Reject All');
        $cookie_settings_btn = get_option('eslt_cookie_settings_btn', 'Cookie Settings');
        $save_preferences_btn = get_option('eslt_save_preferences_btn', 'Save Preferences');
        $close_btn_label = get_option('eslt_close_btn_label', 'Close');
        $technical_cookies_title = get_option('eslt_technical_cookies_title', 'Technical Cookies');
        $technical_cookies_desc = get_option('eslt_technical_cookies_desc', 'These cookies are necessary for the website to function properly.');
        $analytical_cookies_title = get_option('eslt_analytical_cookies_title', 'Analytical Cookies');
        $analytical_cookies_desc = get_option('eslt_analytical_cookies_desc', 'These cookies help us understand how visitors interact with our website.');
        $cookies_info_url = get_option('eslt_cookies_info_url', '');
        $cookies_info_label = get_option('eslt_cookies_info_label', 'More information about cookies');
        $success_message = get_option('eslt_success_message', 'Your cookie preferences have been saved successfully!');
        $youtube_cookies_title = get_option('eslt_youtube_cookies_title', 'YouTube Cookies');
        $youtube_cookies_desc = get_option('eslt_youtube_cookies_desc', 'Allow YouTube to set cookies for enhanced video features.');
        
        // Check if YouTube blocking is enabled
        $disable_youtube = get_option('eslt_disable_youtube_cookies', '0');
        
        // Check if consent cookie exists
        $hidden_class = '';
        if (isset($_COOKIE['eslt-cookies-consent']) && $_COOKIE['eslt-cookies-consent'] === 'true') {
            $hidden_class = 'eslt-hidden';
        }
        
        ?>
        <div id="eslt-cookie-popup" class="eslt-popup-<?php echo esc_attr($position) . ' ' . $hidden_class; ?>">
            <button class="eslt-close-btn" aria-label="<?php echo esc_attr($close_btn_label); ?>">✖</button>
            
            <div class="eslt-popup-content">
                <h3><?php echo esc_html($popup_title); ?></h3>
                <p><?php echo wp_kses($popup_description, array(
                    'br' => array(),
                    'strong' => array(),
                    'b' => array(),
                    'em' => array(),
                    'i' => array(),
                    'u' => array(),
                    'span' => array('class' => array(), 'style' => array()),
                    'a' => array('href' => array(), 'target' => array(), 'rel' => array())
                )); ?></p>
                
                <?php if (!empty($cookies_info_url)): ?>
                <p class="eslt-cookie-info-link">
                    <a href="<?php echo esc_url($cookies_info_url); ?>">
                        <?php echo esc_html($cookies_info_label); ?>
                    </a>
                </p>
                <?php endif; ?>
                
                <div class="eslt-settings-view eslt-hidden">
                    <div class="eslt-cookie-category">
                        <label class="eslt-cookie-label">
                            <input type="checkbox" id="eslt-technical" checked disabled>
                            <span class="eslt-checkbox-custom"></span>
                            <strong><?php echo esc_html($technical_cookies_title); ?></strong>
                        </label>
                        <p class="eslt-cookie-description">
                            <?php echo esc_html($technical_cookies_desc); ?>
                        </p>
                    </div>
                    
                    <div class="eslt-cookie-category">
                        <label class="eslt-cookie-label">
                            <input type="checkbox" id="eslt-analytical">
                            <span class="eslt-checkbox-custom"></span>
                            <strong><?php echo esc_html($analytical_cookies_title); ?></strong>
                        </label>
                        <p class="eslt-cookie-description">
                            <?php echo esc_html($analytical_cookies_desc); ?>
                        </p>
                    </div>

                    <?php if ($disable_youtube === '1'): ?>
                    <div class="eslt-cookie-category">
                        <label class="eslt-cookie-label">
                            <input type="checkbox" id="eslt-youtube">
                            <span class="eslt-checkbox-custom"></span>
                            <strong><?php echo esc_html($youtube_cookies_title); ?></strong>
                        </label>
                        <p class="eslt-cookie-description">
                            <?php echo esc_html($youtube_cookies_desc); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="eslt-buttons" style="padding-top: 0;">
                        <button class="eslt-save-btn" data-action="save-preferences">
                            <?php echo esc_html($save_preferences_btn); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Success Message -->
                <div class="eslt-success-message" style="display: none;">
                    <p><?php echo esc_html($success_message); ?></p>
                </div>

                <div class="eslt-buttons eslt-initial-view">
                    <button class="eslt-accept-btn" data-action="accept-all">
                        <?php echo esc_html($accept_all_btn); ?>
                    </button>
                    <button class="eslt-reject-btn" data-action="reject-all">
                        <?php echo esc_html($reject_all_btn); ?>
                    </button>
                    <button class="eslt-settings-btn" data-action="show-settings">
                        <?php echo esc_html($cookie_settings_btn); ?>
                    </button>
                </div>
                
            </div>
        </div>
        <?php
    }
    
    /**
     * Render shortcode button for reopening popup
     */
    public function render_shortcode_button($atts) {
        $atts = shortcode_atts(array(
            'text' => __('Cookie Settings', 'easylytics'),
            'class' => 'easylytics-settings-btn',
            'style' => ''
        ), $atts, 'easylytics-btn');
        
        $button_text = esc_html($atts['text']);
        $button_class = esc_attr($atts['class']);
        $button_style = esc_attr($atts['style']);
        
        return sprintf(
            '<button type="button" class="%s" onclick="EasyLyticsAPI.showPopup()" style="%s">%s</button>',
            $button_class,
            $button_style,
            $button_text
        );
    }
}