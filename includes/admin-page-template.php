<?php
/**
 * EasyLytics Admin Page Template
 * 
 * Template file for the admin settings page
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php _e('EasyLytics Settings', 'easylytics'); ?></h1>
    
    <div class="nav-tab-wrapper">
        <a href="#general" class="nav-tab nav-tab-active" data-tab="general"><?php _e('General', 'easylytics'); ?></a>
        <a href="#content" class="nav-tab" data-tab="content"><?php _e('Content', 'easylytics'); ?></a>
        <a href="#appearance" class="nav-tab" data-tab="appearance"><?php _e('Appearance', 'easylytics'); ?></a>
        <a href="#tools" class="nav-tab" data-tab="tools"><?php _e('Tools', 'easylytics'); ?></a>
        <a href="#description" class="nav-tab" data-tab="description"><?php _e('Description', 'easylytics'); ?></a>
    </div>
    
    <form method="post" action="">
        <?php wp_nonce_field('easylytics_settings'); ?>
        
        <!-- General Tab -->
        <div id="general-tab" class="tab-content active">
            <?php include EASYLYTICS_PLUGIN_PATH . 'includes/admin-tabs/tab-general.php'; ?>
        </div>
        
        <!-- Content Tab -->
        <div id="content-tab" class="tab-content">
            <?php include EASYLYTICS_PLUGIN_PATH . 'includes/admin-tabs/tab-content.php'; ?>
        </div>
        
        <!-- Appearance Tab -->
        <div id="appearance-tab" class="tab-content">
            <?php include EASYLYTICS_PLUGIN_PATH . 'includes/admin-tabs/tab-appearance.php'; ?>
        </div>
        
        <!-- Tools Tab -->
        <div id="tools-tab" class="tab-content">
            <?php include EASYLYTICS_PLUGIN_PATH . 'includes/admin-tabs/tab-tools.php'; ?>
        </div>
        
        <!-- Description Tab -->
        <div id="description-tab" class="tab-content">
            <?php include EASYLYTICS_PLUGIN_PATH . 'includes/admin-tabs/tab-description.php'; ?>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>

<style>
.tab-content { display: none; }
.tab-content.active { display: block; }
.nav-tab-wrapper { margin-bottom: 20px; }
.color-picker { width: 100px !important; }
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        $('.tab-content').removeClass('active');
        $('#' + tab + '-tab').addClass('active');
    });
    
    // Initialize color pickers
    $('.color-picker').wpColorPicker();
    
    // Reset colors button
    $('#reset-colors').click(function() {
        if (confirm(easyLyticsAdmin.strings.reset_confirm)) {
            $.ajax({
                url: easyLyticsAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'eslt_reset_colors',
                    nonce: easyLyticsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    }
                }
            });
        }
    });
    
    // Reset texts button
    $('#reset-texts').click(function() {
        if (confirm('Are you sure you want to reset all content texts to defaults?')) {
            $.ajax({
                url: easyLyticsAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'eslt_reset_texts',
                    nonce: easyLyticsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        location.reload();
                    }
                }
            });
        }
    });
    
});
</script>