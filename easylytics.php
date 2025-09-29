<?php
/**
 * Plugin Name: EasyLytics
 * Plugin URI: https://wiliak.sk
 * Description: GDPR-compliant cookie consent plugin with Google Analytics 4 integration and multilingual support.
 * Version: 1.4.0
 * Author: wiliak.sk
 * Author URI: https://wiliak.sk
 * Text Domain: easylytics
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EASYLYTICS_VERSION', '1.4.0');
define('EASYLYTICS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EASYLYTICS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EASYLYTICS_PLUGIN_FILE', __FILE__);

/**
 * Autoloader for plugin classes
 */
spl_autoload_register(function ($class) {
    // Only autoload our classes
    if (strpos($class, 'EasyLytics_') !== 0) {
        return;
    }
    
    // Convert class name to file name
    $class_file = 'class-' . str_replace('_', '-', strtolower($class)) . '.php';
    $file_path = EASYLYTICS_PLUGIN_PATH . 'includes/' . $class_file;
    
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

/**
 * Plugin activation hook
 */
function easylytics_activate() {
    require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-core.php';
    EasyLytics_Core::activate();
}
register_activation_hook(__FILE__, 'easylytics_activate');

/**
 * Initialize the plugin
 */
function easylytics_init() {
    // Load core class
    require_once EASYLYTICS_PLUGIN_PATH . 'includes/class-easylytics-core.php';
    
    // Initialize plugin
    $easylytics = new EasyLytics_Core();
    $easylytics->run();
}
add_action('plugins_loaded', 'easylytics_init');