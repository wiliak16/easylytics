<?php
/**
 * EasyLytics Admin Class
 * 
 * Handles admin interface, settings pages, and AJAX handlers
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EasyLytics_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Constructor can be used for initialization if needed
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('EasyLytics Settings', 'easylytics'),
            __('EasyLytics', 'easylytics'),
            'manage_options',
            'easylytics',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        // Basic settings
        register_setting('easylytics_settings', 'eslt_ga4_id');
        register_setting('easylytics_settings', 'eslt_popup_position');
        register_setting('easylytics_settings', 'eslt_disable_youtube_cookies');
        register_setting('easylytics_settings', 'eslt_cookies_info_url');
        register_setting('easylytics_settings', 'eslt_cookies_info_label');
        
        // Color settings
        $color_settings = array_keys(EasyLytics_Core::get_default_colors());
        foreach ($color_settings as $setting) {
            register_setting('easylytics_color_settings', $setting);
        }
        
        // Text settings
        $text_settings = array_keys(EasyLytics_Core::get_default_texts());
        foreach ($text_settings as $setting) {
            register_setting('easylytics_text_settings', $setting);
        }
    }
    
    /**
     * Handle color reset AJAX request
     */
    public function handle_reset_colors() {
        check_ajax_referer('easylytics_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'easylytics'));
        }
        
        $defaults = EasyLytics_Core::get_default_colors();
        foreach ($defaults as $key => $value) {
            update_option($key, $value);
        }
        
        wp_send_json_success(__('Color settings reset to defaults successfully!', 'easylytics'));
    }
    
    /**
     * Handle text reset AJAX request
     */
    public function handle_reset_texts() {
        check_ajax_referer('easylytics_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'easylytics'));
        }
        
        $defaults = EasyLytics_Core::get_default_texts();
        foreach ($defaults as $key => $value) {
            update_option($key, $value);
        }
        
        wp_send_json_success(__('Content settings reset to defaults successfully!', 'easylytics'));
    }
    
    /**
     * Handle conflict scan AJAX request
     */
    public function handle_conflict_scan() {
        check_ajax_referer('easylytics_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'easylytics'));
        }
        
        $conflicts = $this->scan_for_ga_conflicts();
        wp_send_json_success($conflicts);
    }
    
    /**
     * Scan for Google Analytics conflicts
     */
    private function scan_for_ga_conflicts() {
        $conflicts = array();
        $current_plugin = plugin_basename(EASYLYTICS_PLUGIN_FILE);
        
        // Scan active plugins
        $active_plugins = get_option('active_plugins', array());
        foreach ($active_plugins as $plugin) {
            if ($plugin === $current_plugin) {
                continue;
            }
            
            $plugin_file = WP_PLUGIN_DIR . '/' . $plugin;
            if (file_exists($plugin_file)) {
                $plugin_content = file_get_contents($plugin_file);
                if (preg_match('/gtag|google.*analytics|GA_TRACKING_ID|gtm\.js/i', $plugin_content)) {
                    $plugin_data = get_plugin_data($plugin_file);
                    $conflicts[] = array(
                        'type' => 'plugin',
                        'name' => $plugin_data['Name'],
                        'location' => $plugin
                    );
                }
            }
        }
        
        // Scan theme files
        $theme_root = get_theme_root();
        $current_theme = get_stylesheet();
        $theme_dir = $theme_root . '/' . $current_theme;
        
        if (is_dir($theme_dir)) {
            $theme_files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($theme_dir)
            );
            
            foreach ($theme_files as $file) {
                if ($file->isFile() && preg_match('/\.(php|js)$/', $file->getFilename())) {
                    $content = file_get_contents($file->getRealPath());
                    if (preg_match('/gtag|google.*analytics|GA_TRACKING_ID|gtm\.js/i', $content)) {
                        $conflicts[] = array(
                            'type' => 'theme',
                            'name' => $current_theme,
                            'location' => str_replace($theme_dir, '', $file->getRealPath())
                        );
                        break;
                    }
                }
            }
        }
        
        return $conflicts;
    }
    
    /**
     * Render README content for documentation tab
     */
    private function render_readme_content() {
        $readme_file = EASYLYTICS_PLUGIN_PATH . 'README.html';
        
        if (!file_exists($readme_file)) {
            return '<div class="notice notice-error"><p>' . 
                   __('README.html file not found in plugin directory.', 'easylytics') . 
                   '</p></div>';
        }
        
        $readme_content = file_get_contents($readme_file);
        
        if (empty($readme_content)) {
            return '<div class="notice notice-warning"><p>' . 
                   __('README.html file is empty or could not be read.', 'easylytics') . 
                   '</p></div>';
        }
        
        // Extract body content
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $readme_content, $matches)) {
            $body_content = $matches[1];
        } else {
            $body_content = $readme_content;
        }
        
        // Remove style tags
        $body_content = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $body_content);
        
        return '<div class="easylytics-readme-content">' . $body_content . '</div>';
    }
    
    /**
     * Handle settings save
     */
    private function handle_settings_save() {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'easylytics_settings')) {
            wp_die(__('Security check failed', 'easylytics'));
        }
        
        // Save general settings
        update_option('eslt_ga4_id', sanitize_text_field($_POST['eslt_ga4_id']));
        update_option('eslt_popup_position', sanitize_text_field($_POST['eslt_popup_position']));
        update_option('eslt_disable_youtube_cookies', isset($_POST['eslt_disable_youtube_cookies']) ? '1' : '0');
        update_option('eslt_cookies_info_url', esc_url_raw($_POST['eslt_cookies_info_url']));
        update_option('eslt_cookies_info_label', sanitize_text_field($_POST['eslt_cookies_info_label']));
        
        // Save color settings
        $color_fields = array_keys(EasyLytics_Core::get_default_colors());
        foreach ($color_fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                if ($field === 'eslt_font_size') {
                    $value = absint($value);
                    if ($value < 12 || $value > 24) {
                        $value = 16;
                    }
                } else {
                    if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
                        continue;
                    }
                }
                update_option($field, $value);
            }
        }
        
        // Save text settings
        $text_fields = array_keys(EasyLytics_Core::get_default_texts());
        foreach ($text_fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'eslt_popup_description') {
                    $value = wp_kses($_POST[$field], array(
                        'br' => array(),
                        'strong' => array(),
                        'b' => array(),
                        'em' => array(),
                        'i' => array(),
                        'u' => array(),
                        'span' => array('class' => array(), 'style' => array()),
                        'a' => array('href' => array(), 'target' => array(), 'rel' => array())
                    ));
                } elseif ($field === 'eslt_technical_cookies_desc' || $field === 'eslt_analytical_cookies_desc' || $field === 'eslt_youtube_cookies_desc') {
                    $value = sanitize_textarea_field($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_option($field, $value);
            }
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>' . 
             __('Settings saved successfully!', 'easylytics') . '</p></div>';
    }
    
    /**
     * Render admin page
     * 
     * NOTE: This method should be added to the class-easylytics-admin.php file
     * before the closing brace of the class
     */
    public function render_admin_page() {
        if (isset($_POST['submit'])) {
            $this->handle_settings_save();
        }
        
        $ga4_id = get_option('eslt_ga4_id', '');
        $popup_position = get_option('eslt_popup_position', 'bottom-right');
        $disable_youtube_cookies = get_option('eslt_disable_youtube_cookies', '0');
        $cookies_info_url = get_option('eslt_cookies_info_url', '');
        $cookies_info_label = get_option('eslt_cookies_info_label', 'More information about cookies');
        $color_settings = EasyLytics_Core::get_default_colors();
        $text_settings = EasyLytics_Core::get_default_texts();
        
        // Get current values
        foreach ($color_settings as $key => $default) {
            $color_settings[$key] = get_option($key, $default);
        }
        
        foreach ($text_settings as $key => $default) {
            $text_settings[$key] = get_option($key, $default);
        }
        
        // Include the admin page template
        include EASYLYTICS_PLUGIN_PATH . 'includes/admin-page-template.php';
    }
}