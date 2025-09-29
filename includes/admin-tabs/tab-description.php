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

<div id="readme-loading" style="text-align: center; padding: 40px;">
    <p>Loading documentation...</p>
</div>
<div id="readme-content" class="eslt-readme-wrapper"><?php echo $readme_content; ?></div>