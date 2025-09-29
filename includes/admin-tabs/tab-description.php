<?php
/**
 * EasyLytics Admin - Description Tab
 * 
 * Displays README.md content in the admin interface
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$readme_file = EASYLYTICS_PLUGIN_PATH . 'README.md';

if (!file_exists($readme_file)) {
    echo '<div class="notice notice-error"><p>' . 
         __('README.md file not found in plugin directory.', 'easylytics') . 
         '</p></div>';
    return;
}

$readme_content = file_get_contents($readme_file);

if (empty($readme_content)) {
    echo '<div class="notice notice-warning"><p>' . 
         __('README.md file is empty or could not be read.', 'easylytics') . 
         '</p></div>';
    return;
}
?>

<div class="easylytics-readme-content" style="max-width: none;">
    <div style="">
<?php echo esc_html($readme_content); ?>
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-left: 4px solid #2271b1; border-radius: 3px;">
        <p style="margin: 0;">
            <strong><?php _e('Note:', 'easylytics'); ?></strong> 
            <?php _e('This is the raw README.md file. For better formatting, view it on', 'easylytics'); ?> 
            <a href="https://github.com/wiliak16/easylytics" target="_blank">GitHub</a>.
        </p>
    </div>
</div>