<?php
/**
 * EasyLytics Assets Class
 * 
 * Handles enqueuing of scripts and styles for both frontend and admin
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EasyLytics_Assets {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Constructor can be used for initialization if needed
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue frontend CSS
        wp_enqueue_style(
            'easylytics-style',
            EASYLYTICS_PLUGIN_URL . 'assets/css/easylytics.css',
            array(),
            $this->get_file_version(EASYLYTICS_PLUGIN_PATH . 'assets/css/easylytics.css')
        );
        
        // Enqueue frontend JavaScript
        wp_enqueue_script(
            'easylytics-script',
            EASYLYTICS_PLUGIN_URL . 'assets/js/easylytics.js',
            array('jquery'),
            $this->get_file_version(EASYLYTICS_PLUGIN_PATH . 'assets/js/easylytics.js'),
            true
        );
        
        // Localize script with settings
        wp_localize_script('easylytics-script', 'easyLyticsAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('easylytics_nonce'),
            'ga4_id' => get_option('eslt_ga4_id', ''),
            'cookie_settings_hide_btn' => get_option('eslt_cookie_settings_hide_btn', 'Hide Settings'),
            'youtube_blocking_enabled' => get_option('eslt_disable_youtube_cookies', '0') === '1'
        ));
        
        // Add dynamic CSS
        $this->add_dynamic_css();
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on plugin settings page
        if ($hook !== 'settings_page_easylytics') {
            return;
        }
        
        // WordPress color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Admin JavaScript
        wp_enqueue_script(
            'easylytics-admin',
            EASYLYTICS_PLUGIN_URL . 'assets/js/easylytics-admin.js',
            array('jquery', 'wp-color-picker'),
            EASYLYTICS_VERSION,
            true
        );
        
        // Admin CSS
        wp_enqueue_style(
            'easylytics-admin-style',
            EASYLYTICS_PLUGIN_URL . 'assets/css/easylytics-admin.css',
            array(),
            EASYLYTICS_VERSION
        );
        
        // Localize admin script
        wp_localize_script('easylytics-admin', 'easyLyticsAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('easylytics_admin_nonce'),
            'strings' => array(
                'scanning' => __('Scanning...', 'easylytics'),
                'error' => __('Error occurred', 'easylytics'),
                'reset_confirm' => __('Are you sure you want to reset all color settings to defaults?', 'easylytics')
            )
        ));
    }
    
    /**
     * Add dynamic CSS based on color settings
     */
    private function add_dynamic_css() {
        $bg_color = get_option('eslt_bg_color', '#ffffff');
        $text_color = get_option('eslt_text_color', '#374151');
        $primary_btn_bg = get_option('eslt_primary_btn_bg', '#3b82f6');
        $primary_btn_text = get_option('eslt_primary_btn_text', '#ffffff');
        $secondary_btn_bg = get_option('eslt_secondary_btn_bg', '#000000');
        $secondary_btn_text = get_option('eslt_secondary_btn_text', '#ffffff');
        $tertiary_btn_bg = get_option('eslt_tertiary_btn_bg', '#dc2626');
        $tertiary_btn_text = get_option('eslt_tertiary_btn_text', '#ffffff');
        $border_color = get_option('eslt_border_color', '#d1d5db');
        $font_size = get_option('eslt_font_size', '16');
        
        $custom_css = "
        :root {
            --eslt-bg-color: {$bg_color};
            --eslt-text-color: {$text_color};
            --eslt-primary-btn-bg: {$primary_btn_bg};
            --eslt-primary-btn-text: {$primary_btn_text};
            --eslt-secondary-btn-bg: {$secondary_btn_bg};
            --eslt-secondary-btn-text: {$secondary_btn_text};
            --eslt-tertiary-btn-bg: {$tertiary_btn_bg};
            --eslt-tertiary-btn-text: {$tertiary_btn_text};
            --eslt-border-color: {$border_color};
            --eslt-font-size: {$font_size}px;
        }
        
        #eslt-cookie-popup {
            background-color: var(--eslt-bg-color);
            color: var(--eslt-text-color);
            border-color: var(--eslt-border-color);
            font-size: var(--eslt-font-size);
        }
        
        #eslt-cookie-popup .eslt-close-btn {
            color: var(--eslt-text-color);
        }
        
        #eslt-cookie-popup .eslt-close-btn:hover {
            color: #dc2626;
        }
        
        #eslt-cookie-popup .eslt-accept-btn {
            background-color: var(--eslt-primary-btn-bg);
            color: var(--eslt-primary-btn-text);
        }
        
        #eslt-cookie-popup .eslt-settings-btn {
            background-color: var(--eslt-secondary-btn-bg);
            color: var(--eslt-secondary-btn-text);
            border-color: var(--eslt-border-color);
        }
        
        #eslt-cookie-popup .eslt-reject-btn {
            background-color: var(--eslt-tertiary-btn-bg);
            color: var(--eslt-tertiary-btn-text);
        }

        #eslt-cookie-popup .eslt-save-btn {
            background-color: var(--eslt-primary-btn-bg);
            color: var(--eslt-primary-btn-text);
        }
        ";
        
        wp_add_inline_style('easylytics-style', $custom_css);
    }
    
    /**
     * Get file version from file header
     */
    private function get_file_version($file_path) {
        if (!file_exists($file_path)) {
            return EASYLYTICS_VERSION;
        }
        
        $headers = array('Version' => 'Version');
        $file_data = get_file_data($file_path, $headers);
        
        return !empty($file_data['Version']) ? $file_data['Version'] : EASYLYTICS_VERSION;
    }
}