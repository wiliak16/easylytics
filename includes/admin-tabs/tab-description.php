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

<h2><?php _e('Plugin Description & Documentation', 'easylytics'); ?></h2>

<div id="readme-content" style="max-width: none;">
    <?php
    $readme_file = EASYLYTICS_PLUGIN_PATH . 'README.html';
    
    if (file_exists($readme_file)) {
        $readme_content = file_get_contents($readme_file);
        
        if (!empty($readme_content)) {
            // Extract body content
            if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $readme_content, $matches)) {
                $body_content = $matches[1];
            } else {
                $body_content = $readme_content;
            }
            
            // Remove style tags
            $body_content = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $body_content);
            
            echo '<div class="easylytics-readme-content">' . $body_content . '</div>';
        } else {
            echo '<div class="notice notice-warning"><p>' . 
                 __('README.html file is empty or could not be read.', 'easylytics') . 
                 '</p></div>';
        }
    } else {
        echo '<div class="notice notice-error"><p>' . 
             __('README.html file not found in plugin directory.', 'easylytics') . 
             '</p></div>';
    }
    ?>
</div>