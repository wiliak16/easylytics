/**
 * EasyLytics Admin JavaScript
 * Version: 1.2.0
 * Handles color picker, reset functionality, conflict scanning, and admin interface
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Initialize color pickers
        $('.color-picker').wpColorPicker({
            change: function(event, ui) {
                // Update preview in real-time (optional)
                updatePreview();
            },
            clear: function() {
                // Handle color clear
                updatePreview();
            }
        });
        
        // Tab switching functionality
        $('.nav-tab').click(function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').removeClass('active');
            $('#' + tab + '-tab').addClass('active');
        });
        
        // Reset colors button
        $('#reset-colors').click(function() {
            if (confirm(easyLyticsAdmin.strings.reset_confirm)) {
                var $button = $(this);
                $button.prop('disabled', true).text('Resetting...');
                
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
                        } else {
                            alert('Error: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Network error occurred. Please try again.');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Reset to Default Colors');
                    }
                });
            }
        });
        
        // Reset texts button
        $('#reset-texts').click(function() {
            if (confirm(easyLyticsAdmin.strings.reset_texts_confirm)) {
                var $button = $(this);
                $button.prop('disabled', true).text('Resetting...');
                
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
                        } else {
                            alert('Error: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Network error occurred. Please try again.');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Reset Content to Defaults');
                    }
                });
            }
        });
        
        // Conflict scanner
        $('#scan-conflicts').click(function() {
            var $button = $(this);
            var $results = $('#scan-results');
            
            $button.prop('disabled', true).text(easyLyticsAdmin.strings.scanning);
            $results.html('<div class="eslt-notice eslt-notice-info"><p>Scanning for conflicts...</p></div>');
            
            $.ajax({
                url: easyLyticsAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'eslt_scan_conflicts',
                    nonce: easyLyticsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        displayScanResults(response.data, $results);
                    } else {
                        $results.html('<div class="eslt-notice eslt-notice-info"><p>Error: ' + response.data + '</p></div>');
                    }
                },
                error: function() {
                    $results.html('<div class="eslt-notice eslt-notice-info"><p>Network error occurred. Please try again.</p></div>');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Scan for Conflicts');
                }
            });
        });
        
        // Color preview functionality
        function updatePreview() {
            // This would update a live preview if we had one
            // For now, just a placeholder for future enhancement
        }
        
        // Display scan results
        function displayScanResults(conflicts, $container) {
            var html = '<div class="eslt-notice ' + (conflicts.length === 0 ? 'eslt-notice-success' : 'eslt-notice-warning') + '">';
            html += '<p><strong>Scan Results:</strong></p>';
            
            if (conflicts.length === 0) {
                html += '<p>✅ No Google Analytics conflicts detected!</p>';
                html += '<p>Your EasyLytics setup should work perfectly without any conflicts.</p>';
            } else {
                html += '<p>⚠️ Found ' + conflicts.length + ' potential conflict(s):</p>';
                html += '<ul style="margin-left: 20px;">';
                
                conflicts.forEach(function(conflict) {
                    var icon = conflict.type === 'plugin' ? '🔌' : '🎨';
                    html += '<li><strong>' + icon + ' ' + conflict.name + '</strong> (' + 
                           conflict.type + '): <code>' + conflict.location + '</code></li>';
                });
                
                html += '</ul>';
                html += '<h4>Recommendations:</h4>';
                html += '<ul style="margin-left: 20px;">';
                html += '<li>Disable other Google Analytics plugins to avoid duplicate tracking</li>';
                html += '<li>Remove manual GA code from your theme if using EasyLytics</li>';
                html += '<li>Check plugin/theme settings for GA configuration options</li>';
                html += '<li>Test your setup to ensure tracking works correctly</li>';
                html += '</ul>';
            }
            
            html += '<p><small>Scan completed at: ' + new Date().toLocaleString() + '</small></p>';
            html += '</div>';
            
            $container.html(html);
        }
        
        // Form validation
        $('form').on('submit', function(e) {
            var ga4Id = $('#eslt_ga4_id').val();
            
            // Validate GA4 ID format if not empty
            if (ga4Id && !ga4Id.match(/^G-[A-Z0-9]{10}$/)) {
                alert('Please enter a valid GA4 Measurement ID (format: G-XXXXXXXXXX)');
                e.preventDefault();
                $('#eslt_ga4_id').focus();
                return false;
            }
            
            // Validate font size
            var fontSize = $('#eslt_font_size').val();
            if (fontSize && (fontSize < 12 || fontSize > 24)) {
                alert('Font size must be between 12 and 24 pixels');
                e.preventDefault();
                $('#eslt_font_size').focus();
                return false;
            }
            
            // Validate color fields
            var colorFields = [
                'eslt_bg_color', 'eslt_text_color', 'eslt_primary_btn_bg', 
                'eslt_primary_btn_text', 'eslt_secondary_btn_bg', 
                'eslt_secondary_btn_text', 'eslt_border_color'
            ];
            
            var invalidColors = [];
            colorFields.forEach(function(field) {
                var color = $('#' + field).val();
                if (color && !color.match(/^#[a-f0-9]{6}$/i)) {
                    invalidColors.push(field);
                }
            });
            
            if (invalidColors.length > 0) {
                alert('Please enter valid hex colors (format: #ffffff) for all color fields');
                e.preventDefault();
                $('#' + invalidColors[0]).focus();
                return false;
            }
        });
        
        // Auto-format hex colors
        $('.color-picker').on('input', function() {
            var $this = $(this);
            var value = $this.val();
            
            // Remove non-hex characters
            value = value.replace(/[^#a-f0-9]/gi, '');
            
            // Add # if missing
            if (value && !value.startsWith('#')) {
                value = '#' + value;
            }
            
            // Limit to 7 characters
            if (value.length > 7) {
                value = value.substring(0, 7);
            }
            
            $this.val(value);
        });
        
        // Font size validation
        $('#eslt_font_size').on('input', function() {
            var $this = $(this);
            var value = parseInt($this.val());
            
            if (value < 12) {
                $this.val(12);
            } else if (value > 24) {
                $this.val(24);
            }
        });
        
        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('form').submit();
            }
            
            // Ctrl/Cmd + R to reset colors (when on appearance tab)
            if ((e.ctrlKey || e.metaKey) && e.key === 'r' && $('#appearance-tab').hasClass('active')) {
                e.preventDefault();
                $('#reset-colors').click();
            }
        });
        
        // Add tooltips to color fields
        $('.color-picker').each(function() {
            var $this = $(this);
            var label = $this.closest('tr').find('th').text().trim();
            $this.attr('title', 'Click to open color picker for ' + label);
        });
        
        // Show/hide advanced options
        var $advancedToggle = $('<button type="button" class="button button-link" id="toggle-advanced">Show Advanced Options</button>');
        var $advancedSection = $('<div id="advanced-options" style="display: none;"></div>');
        
        // Add some advanced options (placeholder for future features)
        $advancedSection.html(
            '<h3>Advanced Options</h3>' +
            '<p><em>Advanced features will be available in future updates.</em></p>'
        );
        
        // Insert advanced section
        $('#tools-tab').append($advancedToggle).append($advancedSection);
        
        $advancedToggle.click(function() {
            $advancedSection.slideToggle();
            $(this).text($advancedSection.is(':visible') ? 'Hide Advanced Options' : 'Show Advanced Options');
        });
        
        // Add export/import functionality placeholder
        var $exportImport = $(
            '<div class="export-import-section" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ccd0d4;">' +
            '<h3>Export/Import Settings</h3>' +
            '<p>Export your EasyLytics configuration or import settings from a backup.</p>' +
            '<div style="margin: 10px 0;">' +
            '<button type="button" class="button button-secondary" id="export-settings">Export Settings</button> ' +
            '<button type="button" class="button button-secondary" id="import-settings">Import Settings</button>' +
            '</div>' +
            '<input type="file" id="import-file" accept=".json" style="display: none;">' +
            '</div>'
        );
        
        $('#tools-tab').append($exportImport);
        
        // Export settings
        $('#export-settings').click(function() {
            var settings = {
                ga4_id: $('#eslt_ga4_id').val(),
                popup_position: $('#eslt_popup_position').val(),
                forced_locale: $('#eslt_forced_locale').val(),
                colors: {}
            };
            
            $('.color-picker').each(function() {
                settings.colors[$(this).attr('id')] = $(this).val();
            });
            
            settings.font_size = $('#eslt_font_size').val();
            settings.export_date = new Date().toISOString();
            settings.plugin_version = 'EasyLytics 2.1.0';
            
            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(settings, null, 2));
            var downloadAnchor = $('<a></a>').attr("href", dataStr).attr("download", "easylytics-settings.json");
            $('body').append(downloadAnchor);
            downloadAnchor[0].click();
            downloadAnchor.remove();
        });
        
        // Import settings
        $('#import-settings').click(function() {
            $('#import-file').click();
        });
        
        $('#import-file').change(function(e) {
            var file = e.target.files[0];
            if (!file) return;
            
            var reader = new FileReader();
            reader.onload = function(e) {
                try {
                    var settings = JSON.parse(e.target.result);
                    
                    if (confirm('This will overwrite your current settings. Continue?')) {
                        // Import basic settings
                        if (settings.ga4_id) $('#eslt_ga4_id').val(settings.ga4_id);
                        if (settings.popup_position) $('#eslt_popup_position').val(settings.popup_position);
                        if (settings.forced_locale) $('#eslt_forced_locale').val(settings.forced_locale);
                        if (settings.font_size) $('#eslt_font_size').val(settings.font_size);
                        
                        // Import colors
                        if (settings.colors) {
                            Object.keys(settings.colors).forEach(function(key) {
                                $('#' + key).val(settings.colors[key]);
                                $('#' + key).wpColorPicker('color', settings.colors[key]);
                            });
                        }
                        
                        alert('Settings imported successfully! Don\'t forget to save changes.');
                    }
                } catch (error) {
                    alert('Invalid settings file. Please select a valid JSON file exported from EasyLytics.');
                }
            };
            reader.readAsText(file);
        });
        
    });
    
})(jQuery);