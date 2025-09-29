<?php
/**
 * EasyLytics Admin - Tools Tab
 * 
 * @package EasyLytics
 * @version 1.4.0
 */
echo "<!-- TAB-TOOLS.PHP IS LOADING -->";
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- TAB-TOOLS.PHP IS LOADING -->
<h2><?php _e('Tools', 'easylytics'); ?></h2>

<h3><?php _e('Google Analytics Conflict Scanner', 'easylytics'); ?></h3>
<p><?php _e('Scan your website for potential conflicts with other Google Analytics implementations.', 'easylytics'); ?></p>

<button type="button" id="scan-conflicts" class="button button-secondary">
    <?php _e('Scan for Conflicts', 'easylytics'); ?>
</button>

<div id="scan-results" style="margin-top: 20px;">
    <div class="eslt-notice eslt-notice-info">
        <p><?php _e('Click "Scan for Conflicts" to check for other Google Analytics implementations.', 'easylytics'); ?></p>
    </div>
</div>