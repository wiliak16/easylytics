<?php
/**
 * EasyLytics Uninstall Script
 * Version: 1.4.0
 * 
 * This file is executed when the plugin is uninstalled (deleted).
 * It performs a complete cleanup of all plugin data from the database.
 * 
 * @package EasyLytics
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Security check - ensure user has proper capabilities
if (!current_user_can('activate_plugins')) {
    exit;
}

/**
 * Define all plugin options to be deleted
 * Organized by category for better maintenance
 */
$easylytics_options = array(
    
    // Basic Settings
    'eslt_ga4_id',                        // Google Analytics 4 ID
    'eslt_popup_position',                // Popup position (bottom-right, bottom-left, bottom-center)
    'eslt_disable_youtube_cookies',       // YouTube blocking enabled/disabled
    'eslt_cookies_info_url',              // Cookie information URL
    'eslt_cookies_info_label',            // Cookie information link label
    
    // Color Settings
    'eslt_bg_color',                      // Background color
    'eslt_text_color',                    // Text color
    'eslt_primary_btn_bg',                // Primary button background
    'eslt_primary_btn_text',              // Primary button text color
    'eslt_secondary_btn_bg',              // Secondary button background
    'eslt_secondary_btn_text',            // Secondary button text color
    'eslt_tertiary_btn_bg',               // Tertiary button background
    'eslt_tertiary_btn_text',             // Tertiary button text color
    'eslt_border_color',                  // Border color
    'eslt_font_size',                     // Font size
    
    // Text/Content Settings
    'eslt_popup_title',                   // Popup title
    'eslt_popup_description',             // Popup description
    'eslt_accept_all_btn',                // Accept all button text
    'eslt_reject_all_btn',                // Reject all button text
    'eslt_cookie_settings_btn',           // Cookie settings button text
    'eslt_cookie_settings_hide_btn',      // Hide cookie settings button text
    'eslt_save_preferences_btn',          // Save preferences button text
    'eslt_close_btn_label',               // Close button label
    'eslt_technical_cookies_title',       // Technical cookies title
    'eslt_technical_cookies_desc',        // Technical cookies description
    'eslt_analytical_cookies_title',      // Analytical cookies title
    'eslt_analytical_cookies_desc',       // Analytical cookies description
    'eslt_youtube_cookies_title',         // YouTube cookies title
    'eslt_youtube_cookies_desc',          // YouTube cookies description
    'eslt_success_message',               // Success message
    'eslt_youtube_blocked_title',         // YouTube blocked title
    'eslt_youtube_blocked_message',       // YouTube blocked message
    'eslt_youtube_accept_button',         // YouTube accept button text
    
    // Cookie Settings (if stored in options)
    'eslt_youtube_cookies',               // YouTube cookies consent state
    
    // Plugin Meta
    'eslt_version',                       // Plugin version
    'eslt_activation_time',               // Plugin activation timestamp
    
    // Legacy options (from older versions, if any)
    'eslt_popup_theme',                   // Old theme option
    'eslt_block_youtube_cookies',         // Old YouTube blocking option
    'eslt_ajax_method',                   // Old AJAX method option
    'eslt_cookie_validity_days',          // Old cookie validity option
    'eslt_popup_bg_color',                // Old naming convention
    'eslt_popup_text_color',              // Old naming convention
    'eslt_primary_button_bg',             // Old naming convention
    'eslt_primary_button_text',           // Old naming convention
    'eslt_secondary_button_bg',           // Old naming convention
    'eslt_secondary_button_text',         // Old naming convention
    'eslt_secondary_button_border',       // Old naming convention
    'eslt_font_family',                   // Old font family option
    'eslt_forced_locale',                 // If language forcing was implemented
    'eslt_cookie_info_url',               // Old naming convention
);

/**
 * Delete all plugin options
 */
foreach ($easylytics_options as $option) {
    delete_option($option);
    
    // For multisite installations
    if (is_multisite()) {
        delete_site_option($option);
    }
}

/**
 * Delete any transients that might have been set
 */
delete_transient('eslt_cache');
delete_transient('eslt_ga_conflicts_cache');
delete_transient('eslt_plugin_data_cache');

// For multisite
if (is_multisite()) {
    delete_site_transient('eslt_cache');
    delete_site_transient('eslt_ga_conflicts_cache');
    delete_site_transient('eslt_plugin_data_cache');
}

/**
 * Clean up user meta
 */
global $wpdb;

// Delete any user meta related to the plugin
$user_meta_keys = array(
    'eslt_admin_notice_dismissed',
    'eslt_welcome_notice_dismissed',
    'eslt_update_notice_dismissed',
    'eslt_review_notice_dismissed',
    'eslt_user_preferences',
);

foreach ($user_meta_keys as $meta_key) {
    $wpdb->delete(
        $wpdb->usermeta,
        array('meta_key' => $meta_key),
        array('%s')
    );
}

/**
 * Clean up post meta
 */
$post_meta_keys = array(
    'eslt_exclude',
    'eslt_custom_settings',
    'eslt_page_specific_ga4',
);

foreach ($post_meta_keys as $meta_key) {
    $wpdb->delete(
        $wpdb->postmeta,
        array('meta_key' => $meta_key),
        array('%s')
    );
}

/**
 * Clean up term meta (if used)
 */
if (!empty($wpdb->termmeta)) {
    $term_meta_keys = array(
        'eslt_category_settings',
    );
    
    foreach ($term_meta_keys as $meta_key) {
        $wpdb->delete(
            $wpdb->termmeta,
            array('meta_key' => $meta_key),
            array('%s')
        );
    }
}

/**
 * Remove custom database tables if they exist
 * (The plugin doesn't create custom tables, but keeping for future use)
 */
$table_name = $wpdb->prefix . 'easylytics_logs';
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

$table_name = $wpdb->prefix . 'easylytics_consent_logs';
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

/**
 * Clean up any scheduled cron events
 */
$cron_events = array(
    'eslt_daily_cleanup',
    'eslt_weekly_report',
    'eslt_check_updates',
);

foreach ($cron_events as $event) {
    $timestamp = wp_next_scheduled($event);
    if ($timestamp) {
        wp_unschedule_event($timestamp, $event);
    }
    // Clear all scheduled hooks in case of multiple schedules
    wp_clear_scheduled_hook($event);
}

/**
 * Remove any uploaded files or directories created by the plugin
 */
$upload_dir = wp_upload_dir();
$plugin_upload_dir = $upload_dir['basedir'] . '/easylytics/';

if (is_dir($plugin_upload_dir)) {
    // Recursively delete the directory and all its contents
    easylytics_delete_directory($plugin_upload_dir);
}

/**
 * Helper function to recursively delete a directory
 */
function easylytics_delete_directory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    
    rmdir($dir);
}

/**
 * Clear any cached data
 */
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Clear object cache if available
if (function_exists('wp_cache_delete')) {
    wp_cache_delete('easylytics_settings', 'options');
    wp_cache_delete('easylytics_cache', 'easylytics');
}

/**
 * Clear rewrite rules
 * (In case the plugin added any custom rewrite rules)
 */
flush_rewrite_rules();

/**
 * Log uninstallation if debugging is enabled
 */
if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
    error_log('EasyLytics: Plugin uninstalled and all data cleaned up at ' . current_time('mysql'));
}

/**
 * Trigger action hook for third-party cleanup
 * Allows other plugins/themes to perform cleanup when EasyLytics is uninstalled
 */
do_action('easylytics_uninstall');

/**
 * Final cleanup for multisite
 */
if (is_multisite()) {
    global $wp_version;
    
    // For WordPress 4.6+
    if (version_compare($wp_version, '4.6', '>=')) {
        $sites = get_sites();
        
        foreach ($sites as $site) {
            switch_to_blog($site->blog_id);
            
            // Delete options for each site
            foreach ($easylytics_options as $option) {
                delete_option($option);
            }
            
            // Clear transients for each site
            delete_transient('eslt_cache');
            delete_transient('eslt_ga_conflicts_cache');
            
            restore_current_blog();
        }
    }
}

/**
 * Note: We intentionally do NOT delete the cookies from users' browsers
 * as that would require JavaScript and this file runs server-side.
 * Users will need to clear their own cookies if desired.
 * 
 * Cookies that users may have:
 * - eslt-cookies-consent
 * - eslt-tech-cookies-consent
 * - eslt-analytical-cookies
 * - eslt-youtube-cookies
 */