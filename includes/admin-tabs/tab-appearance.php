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
<h2><?php _e('Appearance Settings', 'easylytics'); ?></h2>

<div style="display: flex; gap: 20px; margin-bottom: 20px;">
    <button type="button" id="reset-colors" class="button button-secondary">
        <?php _e('Reset to Default Colors', 'easylytics'); ?>
    </button>
</div>

<table class="form-table">
    <tr>
        <th scope="row"><?php _e('Background Color', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_bg_color" name="eslt_bg_color" 
                   value="<?php echo esc_attr($color_settings['eslt_bg_color']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Text Color', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_text_color" name="eslt_text_color" 
                   value="<?php echo esc_attr($color_settings['eslt_text_color']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Primary Button Background', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_primary_btn_bg" name="eslt_primary_btn_bg" 
                   value="<?php echo esc_attr($color_settings['eslt_primary_btn_bg']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Primary Button Text', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_primary_btn_text" name="eslt_primary_btn_text" 
                   value="<?php echo esc_attr($color_settings['eslt_primary_btn_text']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Secondary Button Background', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_secondary_btn_bg" name="eslt_secondary_btn_bg" 
                   value="<?php echo esc_attr($color_settings['eslt_secondary_btn_bg']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Secondary Button Text', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_secondary_btn_text" name="eslt_secondary_btn_text" 
                   value="<?php echo esc_attr($color_settings['eslt_secondary_btn_text']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row"><?php _e('Tertiary Button Background', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_tertiary_btn_bg" name="eslt_tertiary_btn_bg" 
                   value="<?php echo esc_attr($color_settings['eslt_tertiary_btn_bg']); ?>" 
                   class="color-picker" />
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Tertiary Button Text', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_tertiary_btn_text" name="eslt_tertiary_btn_text" 
                   value="<?php echo esc_attr($color_settings['eslt_tertiary_btn_text']); ?>" 
                   class="color-picker" />
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Border Color', 'easylytics'); ?></th>
        <td>
            <input type="text" id="eslt_border_color" name="eslt_border_color" 
                   value="<?php echo esc_attr($color_settings['eslt_border_color']); ?>" 
                   class="color-picker" />
        </td>
    </tr>
    
    <tr>
        <th scope="row">
            <label for="eslt_font_size"><?php _e('Font Size (px)', 'easylytics'); ?></label>
        </th>
        <td>
            <input type="number" id="eslt_font_size" name="eslt_font_size" 
                   value="<?php echo esc_attr($color_settings['eslt_font_size']); ?>" 
                   min="12" max="24" class="small-text" />
        </td>
    </tr>
</table>