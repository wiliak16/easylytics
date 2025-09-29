<?php
/**
 * EasyLytics Core Class
 * 
 * Main plugin class that orchestrates all components
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EasyLytics_Core {
    
    /**
     * Plugin components
     */
    private $admin;
    private $frontend;
    private $youtube;
    private $assets;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Components will be initialized in run()
    }
    
    /**
     * Run the plugin
     */
    public function run() {
        // Load text domain
        add_action('init', array($this, 'load_textdomain'));
        
        // Initialize components
        $this->init_components();
        
        // Register hooks
        $this->register_hooks();
    }
    
    /**
     * Initialize plugin components
     */
    private function init_components() {
        // Load component classes
        require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-assets.php';
        require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-youtube.php';
        require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-frontend.php';
        require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-admin.php';
        
        // Initialize components
        $this->assets = new EasyLytics_Assets();
        $this->youtube = new EasyLytics_YouTube();
        $this->frontend = new EasyLytics_Frontend();
        $this->admin = new EasyLytics_Admin();
    }
    
    /**
     * Register plugin hooks
     */
    private function register_hooks() {
        // Assets hooks
        add_action('wp_enqueue_scripts', array($this->assets, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this->assets, 'enqueue_admin_assets'));
        
        // Admin hooks
        add_action('admin_menu', array($this->admin, 'add_admin_menu'));
        add_action('admin_init', array($this->admin, 'register_settings'));
        add_action('wp_ajax_eslt_scan_conflicts', array($this->admin, 'handle_conflict_scan'));
        add_action('wp_ajax_eslt_reset_colors', array($this->admin, 'handle_reset_colors'));
        add_action('wp_ajax_eslt_reset_texts', array($this->admin, 'handle_reset_texts'));
        
        // Frontend hooks
        if (!is_admin()) {
            add_action('wp_footer', array($this->frontend, 'render_popup'));
        }
        
        // YouTube hooks
        add_filter('the_content', array($this->youtube, 'process_content'), 99);
        add_filter('widget_text', array($this->youtube, 'process_content'), 99);
        add_filter('render_block', array($this->youtube, 'filter_block_content'), 10, 2);
        
        // Shortcode
        add_shortcode('easylytics-btn', array($this->frontend, 'render_shortcode_button'));
    }
    
    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'easylytics',
            false,
            dirname(plugin_basename(EASYLYTICS_PLUGIN_FILE)) . '/languages'
        );
    }
    
    /**
     * Get default color settings
     */
    public static function get_default_colors() {
        return array(
            'eslt_bg_color' => '#ffffff',
            'eslt_text_color' => '#374151',
            'eslt_primary_btn_bg' => '#3b82f6',
            'eslt_primary_btn_text' => '#ffffff',
            'eslt_secondary_btn_bg' => '#000000',
            'eslt_secondary_btn_text' => '#ffffff',
            'eslt_tertiary_btn_bg' => '#dc2626',
            'eslt_tertiary_btn_text' => '#ffffff',
            'eslt_border_color' => '#d1d5db',
            'eslt_font_size' => '16'
        );
    }
    
    /**
     * Get default text settings
     */
    public static function get_default_texts() {
        return array(
            'eslt_popup_title' => 'We use cookies',
            'eslt_popup_description' => 'We use cookies to improve your experience on our website and to analyze traffic.<br>Choose which cookies you want to allow.',
            'eslt_accept_all_btn' => 'Accept All',
            'eslt_reject_all_btn' => 'Reject All',
            'eslt_cookie_settings_btn' => 'Cookie Settings',
            'eslt_cookie_settings_hide_btn' => 'Hide Settings',
            'eslt_save_preferences_btn' => 'Save Preferences',
            'eslt_close_btn_label' => 'Close',
            'eslt_technical_cookies_title' => 'Technical Cookies',
            'eslt_technical_cookies_desc' => 'These cookies are necessary for the website to function properly.',
            'eslt_analytical_cookies_title' => 'Analytical Cookies',
            'eslt_analytical_cookies_desc' => 'These cookies help us understand how visitors interact with our website.',
            'eslt_youtube_cookies_title' => 'YouTube Cookies',
            'eslt_youtube_cookies_desc' => 'Allow YouTube to set cookies for enhanced video features.',
            'eslt_success_message' => 'Your cookie preferences have been saved successfully!',
            'eslt_youtube_blocked_title' => 'YouTube Video Blocked',
            'eslt_youtube_blocked_message' => 'This video requires YouTube cookies to play.',
            'eslt_youtube_accept_button' => 'Allow YouTube Cookies & Play'
        );
    }
    
    /**
     * Plugin activation
     */
    public static function activate() {
        // Set default options if they don't exist
        $defaults = array_merge(
            self::get_default_colors(),
            self::get_default_texts(),
            array(
                'eslt_ga4_id' => '',
                'eslt_popup_position' => 'bottom-right',
                'eslt_disable_youtube_cookies' => '0',
                'eslt_cookies_info_url' => '',
                'eslt_cookies_info_label' => 'More information about cookies',
                'eslt_version' => EASYLYTICS_VERSION,
                'eslt_activation_time' => time()
            )
        );
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
        
        // Handle YouTube cookies - default to blocked for GDPR compliance
        if (get_option('eslt_youtube_cookies') === false) {
            add_option('eslt_youtube_cookies', 'false');
        }
    }
}