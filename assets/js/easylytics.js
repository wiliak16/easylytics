/**
 * EasyLytics Cookie Consent JavaScript
 * Version: 1.4.0
 * Updated with close button functionality, toggle settings, enhanced GA4 loading,
 * proper admin setting retrieval for hide button text, and cleaned debug logs
 */

(function($) {
    'use strict';
    
    const EasyLytics = {
        
        /**
         * Initialize the plugin
         */
        init: function() {
            this.bindEvents();
            this.showPopup();
            this.handleKeyboardNavigation();
        },
        
        /**
         * Bind event handlers (can be called independently)
         */
        bindEvents: function() {
            const self = this;
            
            // Unbind existing events first to prevent duplicates
            $(document).off('click.easylytics');
            $(document).off('change.easylytics');
            $(document).off('keydown.easylytics');
            
            // Close button click
            $(document).on('click.easylytics', '.eslt-close-btn', function(e) {
                e.preventDefault();
                self.handleClose();
            });
            
            // Accept all button click
            $(document).on('click.easylytics', '.eslt-accept-btn', function(e) {
                e.preventDefault();
                self.handleAcceptAll();
            });
            
            // Reject all button click
            $(document).on('click.easylytics', '.eslt-reject-btn', function(e) {
                e.preventDefault();
                self.handleRejectAll();
            });            

            // Settings button click
            $(document).on('click.easylytics', '.eslt-settings-btn', function(e) {
                e.preventDefault();
                self.toggleSettings();
            });
            
            // Save preferences button click
            $(document).on('click.easylytics', '.eslt-save-btn', function(e) {
                e.preventDefault();
                self.handleSavePreferences();
            });
            
            // ESC key to close popup
            $(document).on('keydown.easylytics', function(e) {
                if (e.keyCode === 27 && $('#eslt-cookie-popup').is(':visible')) {
                    self.handleClose();
                }
            });

            // YouTube accept button click
            $(document).on('click.easylytics', '.eslt-youtube-accept-btn', function(e) {
                e.preventDefault();
                const videoId = $(this).data('video-id');
                self.handleYouTubeConsent(videoId);
            });

        },
        
        /**
         * Handle close button click
         */
        handleClose: function() {
            // Simply close the popup without saving any cookies
            // Popup will appear again on next page load/refresh
            this.hidePopup();
            
            // Trigger custom event for close without consent
            $(document).trigger('easylytics:popupClosed', {
                method: 'close-button',
                consentGiven: false
            });
        },
        
        /**
         * Show success message
         */
        showSuccessMessage: function() {
            const $popup = $('#eslt-cookie-popup');
            const $successMessage = $popup.find('.eslt-success-message');
            const duration = this.getAnimationDuration();
            
            if ($successMessage.length) {
                $successMessage.slideDown(duration);
                
                // Hide the success message after 3 seconds
                setTimeout(function() {
                    $successMessage.slideUp(duration);
                }, 3000);
            }
        },
        
        /**
         * Handle accept all button click
         */
        handleAcceptAll: function() {
            this.setCookie('eslt-cookies-consent', 'true', 7);
            this.setCookie('eslt-tech-cookies-consent', 'true', 7);
            this.setCookie('eslt-analytical-cookies', 'true', 7);
            
            // Only set YouTube cookie if blocking is enabled
            if (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled) {
                this.setCookie('eslt-youtube-cookies', 'true', 7);
            }

            // Show success message first
            this.showSuccessMessage();
            
            // Load GA4 immediately
            this.loadGA4();
            
            // Trigger consent event for other integrations
            this.triggerConsentEvent();
            
            // Hide popup after showing success message (delay for user to see it)
            setTimeout(() => {
                this.hidePopup();
            }, 1500);
            
            // Trigger custom event
            $(document).trigger('easylytics:consentGiven', {
                technical: true,
                analytical: true,
                youtube: typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled ? true : null,
                method: 'accept-all'
            });
        },
        
        /**
         * Handle reject all button click
         */
        handleRejectAll: function() {
            // Set consent to false for all non-essential cookies
            this.setCookie('eslt-cookies-consent', 'true', 7);  // General consent given (user made a choice)
            this.setCookie('eslt-tech-cookies-consent', 'true', 7);  // Technical always required
            this.setCookie('eslt-analytical-cookies', 'false', 7);  // Reject analytical
            
            // Only set YouTube cookie if blocking is enabled
            if (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled) {
                this.setCookie('eslt-youtube-cookies', 'false', 7);  // Reject YouTube
            }

            // Show success message first
            this.showSuccessMessage();
            
            // Do NOT load GA4 since analytical cookies were rejected
            
            // Hide popup after showing success message
            setTimeout(() => {
                this.hidePopup();
            }, 1500);
            
            // Trigger custom event
            $(document).trigger('easylytics:consentGiven', {
                technical: true,
                analytical: false,
                youtube: false,
                method: 'reject-all'
            });
        },        

        /**
         * Toggle settings view
         */
        toggleSettings: function() {
            const $popup = $('#eslt-cookie-popup');
            const $settingsView = $popup.find('.eslt-settings-view');
            const $settingsBtn = $popup.find('.eslt-settings-btn');
            const duration = this.getAnimationDuration();
            
            // Check if settings are currently visible (not hidden by class)
            const isHidden = $settingsView.hasClass('eslt-hidden');
            
            if (!isHidden) {
                // Hide settings using slideUp and add hidden class
                $settingsBtn.removeClass('eslt-active');
                const originalText = $settingsBtn.data('original-text');
                if (originalText) {
                    $settingsBtn.text(originalText);
                }
                
                $popup.css('overflow-y', 'hidden');
                $settingsView.slideUp(duration, function() {
                    $settingsView.addClass('eslt-hidden');
                    $popup.css('overflow-y', 'auto');
                });
                
            } else {
                // Show settings using slideDown
                
                // First, check current analytical consent, default to true if no cookie exists
                let analyticalConsent = this.getAnalyticalPreference();
                
                // If no analytical cookie exists yet, default to checked
                if (this.getCookie('eslt-analytical-cookies') === null) {
                    analyticalConsent = true;
                }
                
                // Only handle YouTube if blocking is enabled
                if (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled) {
                    let youtubeConsent = this.getYouTubePreference();
                    if (this.getCookie('eslt-youtube-cookies') === null) {
                        youtubeConsent = false;
                    }
                    $('#eslt-youtube').prop('checked', youtubeConsent);
                }
                
                $('#eslt-analytical').prop('checked', analyticalConsent);
                
                // Store original text if not already stored
                if (!$settingsBtn.data('original-text')) {
                    $settingsBtn.data('original-text', $settingsBtn.text());
                }
                
                // Update button appearance and text
                $settingsBtn.addClass('eslt-active');
                
                // Use custom hide text from admin settings or fallback
                const hideText = (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.cookie_settings_hide_btn) 
                    ? easyLyticsAjax.cookie_settings_hide_btn 
                    : 'Hide Settings';
                $settingsBtn.text(hideText);
                
                // Remove hidden class first, then animate
                $popup.css('overflow-y', 'hidden');
                $settingsView.slideDown(duration, function() {
                    $settingsView.addClass('eslt-show').removeClass('eslt-hidden');
                    $popup.css('overflow-y', 'auto');
                    // Focus on first checkbox for accessibility
                    setTimeout(function() {
                        $('#eslt-analytical').focus();
                    }, 50);
                });
            }
        },
        
        /**
         * Show settings view (for backward compatibility)
         */
        showSettings: function() {
            this.toggleSettings();
        },
        
        /**
         * Handle save preferences
         */
        handleSavePreferences: function() {
            const analyticalConsent = $('#eslt-analytical').is(':checked');
            
            this.setCookie('eslt-cookies-consent', 'true', 7);
            this.setCookie('eslt-tech-cookies-consent', 'true', 7);
            this.setCookie('eslt-analytical-cookies', analyticalConsent ? 'true' : 'false', 7);
            
            // Only handle YouTube cookies if blocking is enabled
            let youtubeConsent = false;
            if (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled) {
                youtubeConsent = $('#eslt-youtube').is(':checked');
                this.setCookie('eslt-youtube-cookies', youtubeConsent ? 'true' : 'false', 7);
            }
            
            // Show success message first
            this.showSuccessMessage();
            
            if (analyticalConsent) {
                this.loadGA4();
                this.triggerConsentEvent();
            }
            
            // Only process YouTube blocking/unblocking if it's enabled
            if (typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.youtube_blocking_enabled) {
                if (youtubeConsent) {
                    this.unblockYouTubeVideos();
                } else {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }
            
            // Hide popup after showing success message (delay for user to see it)
            setTimeout(() => {
                this.hidePopup();
            }, 1500);
            
            // Trigger custom event
            $(document).trigger('easylytics:consentGiven', {
                technical: true,
                analytical: analyticalConsent,
                youtube: youtubeConsent,
                method: 'save-preferences'
            });
        },
        
        /**
         * Update analytical consent
         */
        updateAnalyticalConsent: function(consent) {
            // This method can be called by external code to update consent
            if (consent) {
                this.setCookie('eslt-analytical-cookies', 'true', 7);
                this.loadGA4();
            } else {
                this.setCookie('eslt-analytical-cookies', 'false', 7);
                // Optionally disable GA4 tracking here
            }
            
            // Trigger consent event
            this.triggerConsentEvent();
        },


        /**
         * Handle YouTube consent from banner button
         */
        handleYouTubeConsent: function(videoId) {
            // Set general cookie consent
            this.setCookie('eslt-cookies-consent', 'true', 7);
            
            // Set technical cookie consent
            this.setCookie('eslt-tech-cookies-consent', 'true', 7);
            
            // Set YouTube cookie consent to true
            this.setCookie('eslt-youtube-cookies', 'true', 7);
            
            // Replace all blocked YouTube videos with actual embeds
            this.unblockYouTubeVideos();
            
            // Trigger consent event
            $(document).trigger('easylytics:youtubeConsentGiven', { videoId: videoId });
            
            // Also trigger general consent event
            $(document).trigger('easylytics:consentGiven', {
                technical: true,
                analytical: this.getAnalyticalPreference(),
                youtube: true,
                method: 'youtube-consent'
            });
            
            // Hide the main cookie popup if it's visible
            this.hidePopup();
        },

        /**
         * Unblock all YouTube videos on page
         */
        unblockYouTubeVideos: function() {
            $('.eslt-youtube-wrapper').each(function() {
                const $wrapper = $(this);
                // Find the previous sibling iframe (not child)
                const $iframe = $wrapper.prev('iframe.eslt-youtube-blurred');
                
                if ($iframe.length) {
                    // Restore src from data-src
                    const dataSrc = $iframe.attr('data-src');
                    if (dataSrc) {
                        $iframe.attr('src', dataSrc);
                        $iframe.removeAttr('data-src');
                    }
                    
                    // Remove blur class
                    $iframe.removeClass('eslt-youtube-blurred');
                }
                
                // Remove the wrapper entirely
                $wrapper.remove();
            });
        },

        /**
         * Get YouTube preference
         */
        getYouTubePreference: function() {
            return this.getCookie('eslt-youtube-cookies') === 'true';
        },        

        
        /**
         * Get animation duration based on user preferences
         */
        getAnimationDuration: function() {
            // Check for reduced motion preference
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return 0; // No animation
            }
            return 500; // Normal animation duration
        },
        
        /**
         * Show popup
         */
        showPopup: function() {
            console.log('show');
            const $popup = $('#eslt-cookie-popup');
            if ($popup.length) {
                const duration = this.getAnimationDuration();
                
                // Ensure settings section is properly reset
                const $settingsView = $popup.find('.eslt-settings-view');
                $settingsView.addClass('eslt-hidden').removeClass('eslt-show');
                
                // Reset settings button
                const $settingsBtn = $popup.find('.eslt-settings-btn');
                $settingsBtn.removeClass('eslt-active');
                
                // Set initial hidden state
                //$popup.hide().removeClass('eslt-show');
                
                // Use jQuery slideDown animation
                $popup.slideDown(duration, function() {
                    //$popup.addClass('eslt-show');
                    
                    // Focus on the popup for accessibility
                    setTimeout(function() {
                        $popup.find('.eslt-accept-btn').focus();
                    }, 100);
                });
                
                // Trigger custom event
                $(document).trigger('easylytics:popupShown');
            }
        },
        
        /**
         * Hide popup
         */
        hidePopup: function() {
            const $popup = $('#eslt-cookie-popup');
            if ($popup.length) {
                const duration = this.getAnimationDuration();
                
                //$popup.removeClass('eslt-show');
                
                // Use jQuery slideUp animation
                $popup.slideUp(duration);
                
                // Trigger custom event
                $(document).trigger('easylytics:popupHidden');
            }
        },
        
        /**
         * Handle keyboard navigation
         */
        handleKeyboardNavigation: function() {
            const $popup = $('#eslt-cookie-popup');
            
            // Trap focus within popup
            $popup.on('keydown', function(e) {
                if (e.keyCode === 9) { // Tab key
                    const focusableElements = $popup.find('button:visible, input:visible, a:visible, [tabindex]:visible');
                    const firstElement = focusableElements.first();
                    const lastElement = focusableElements.last();
                    
                    if (e.shiftKey) {
                        // Shift + Tab
                        if ($(document.activeElement).is(firstElement)) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        // Tab
                        if ($(document.activeElement).is(lastElement)) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        },
        
        /**
         * Load Google Analytics 4
         */
        loadGA4: function() {
            if (typeof easyLyticsAjax === 'undefined' || !easyLyticsAjax.ga4_id) {
                console.error('EasyLytics: GA4 ID not configured or easyLyticsAjax not available');
                return false;
            }
            
            const ga4Id = easyLyticsAjax.ga4_id;
            
            // Prevent multiple GA4 loads - check for both gtag function and our custom marker
            if (window.gtag && (window.easyLyticsGA4Loaded || (window.dataLayer && window.dataLayer.length > 0))) {
                return true;
            }
            
            // Mark as loading to prevent duplicate calls
            window.easyLyticsGA4Loaded = true;
            
            // Double-check analytical consent before loading
            if (!this.getAnalyticalPreference()) {
                return false;
            }
            
            try {
                // Initialize dataLayer first
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                window.gtag = gtag;
                
                // Set initial gtag data
                gtag('js', new Date());
                
                // Load GA4 script
                const script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=' + ga4Id;
                
                script.onload = function() {
                    // Configure GA4 AFTER script loads - this will send automatic page_view
                    gtag('config', ga4Id, {
                        'anonymize_ip': true,
                        'allow_google_signals': false,
                        'allow_ad_personalization_signals': false,
                        'cookie_expires': 60 * 60 * 24 * 7, // 7 days
                        'cookie_update': true,
                        'cookie_flags': 'max-age=' + (60 * 60 * 24 * 7) + ';secure;samesite=lax'
                    });
                    
                    // Trigger our custom event to indicate GA4 is ready
                    $(document).trigger('easylytics:ga4Ready', { ga4_id: ga4Id });
                };
                
                script.onerror = function() {
                    console.error('EasyLytics: Failed to load GA4 script from:', script.src);
                };
                
                document.head.appendChild(script);
                
                // Trigger custom event
                $(document).trigger('easylytics:ga4Loaded', { ga4_id: ga4Id });
                
                return true;
                
            } catch (error) {
                console.error('EasyLytics: Error loading GA4:', error);
                return false;
            }
        },
        
        /**
         * Trigger consent event for third-party integrations
         */
        triggerConsentEvent: function() {
            // Dispatch consent event for YouTube and other services
            const consentEvent = new CustomEvent('easylytics_consent', {
                detail: {
                    technical: true,
                    analytical: this.getAnalyticalPreference(),
                    timestamp: Date.now()
                }
            });
            
            document.dispatchEvent(consentEvent);
            
            // Also trigger jQuery event for backward compatibility
            $(document).trigger('easylytics:consentEvent', {
                technical: true,
                analytical: this.getAnalyticalPreference()
            });
        },
        
        /**
         * Set cookie
         */
        setCookie: function(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            
            document.cookie = name + '=' + encodeURIComponent(value) + 
                            ';expires=' + expires.toUTCString() + 
                            ';path=/;SameSite=Lax;Secure=' + (location.protocol === 'https:');
        },
        
        /**
         * Get cookie
         */
        getCookie: function(name) {
            const nameEQ = name + '=';
            const ca = document.cookie.split(';');
            
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) === 0) {
                    return decodeURIComponent(c.substring(nameEQ.length, c.length));
                }
            }
            return null;
        },
        
        /**
         * Check if user has given consent
         */
        hasConsent: function() {
            return this.getCookie('eslt-cookies-consent') === 'true';
        },
        
        /**
         * Get analytical preference
         */
        getAnalyticalPreference: function() {
            return this.getCookie('eslt-analytical-cookies') === 'true';
        },
        
        /**
         * Public API for external use
         */
        api: {
            // Check if consent has been given
            hasConsent: function() {
                return EasyLytics.hasConsent();
            },
            
            // Get analytical preference
            getAnalyticalPreference: function() {
                return EasyLytics.getAnalyticalPreference();
            },
            
            // Update analytical consent programmatically
            updateAnalyticalConsent: function(consent) {
                EasyLytics.updateAnalyticalConsent(consent);
            },
            
            // Show popup again (useful for settings pages)
            showPopup: function() {
                console.log('showPopup.API');
                const $popup = $('#eslt-cookie-popup');
                if ($popup.length) {
                    const duration = EasyLytics.getAnimationDuration();
                    
                    // Ensure events are bound (important for shortcode usage)
                    EasyLytics.bindEvents();
                    EasyLytics.handleKeyboardNavigation();
                    
                    // Only reset if popup is not currently visible
                    console.log('is visible?', $popup.is(':visible'), $popup.hasClass('eslt-show'));
                    if (!$popup.hasClass('eslt-show') && !$popup.is(':visible')) {
                        console.log('not visible, resetting');
                        // Reset popup to initial state - keep initial view visible, hide settings
                        $popup.find('.eslt-initial-view').show();
                        const $settingsView = $popup.find('.eslt-settings-view');
                        $settingsView.addClass('eslt-hidden').removeClass('eslt-show').css("display","none");;
                        
                        // Reset settings button state
                        const $settingsBtn = $popup.find('.eslt-settings-btn');
                        $settingsBtn.removeClass('eslt-active');
                        const originalText = $settingsBtn.data('original-text');
                        if (originalText) {
                            $settingsBtn.text(originalText);
                        }
                        
                        // Set initial hidden state for popup
                        //$popup.hide().removeClass('eslt-show');
                        
                        // Use jQuery slideDown animation
                        $popup.slideDown(duration, function() {
                            //$popup.addClass('eslt-show');
                            
                            // Focus on accept button
                            setTimeout(function() {
                                $popup.find('.eslt-accept-btn').focus();
                            }, 100);
                        });
                    } else {
                        // Just ensure focus if already visible
                        setTimeout(function() {
                            $popup.find('.eslt-accept-btn').focus();
                        }, 100);
                    }
                    
                    // Trigger custom event
                    $(document).trigger('easylytics:popupReopened');
                }
            },
            
            // Revoke consent (useful for privacy settings)
            revokeConsent: function() {
                EasyLytics.setCookie('eslt-cookies-consent', 'false', -1);
                EasyLytics.setCookie('eslt-tech-cookies-consent', 'false', -1);
                EasyLytics.setCookie('eslt-analytical-cookies', 'false', -1);
                
                // Reload page to reset everything
                if (confirm('This will reload the page to reset all cookies. Continue?')) {
                    location.reload();
                }
            },
            
            // Get current consent status
            getConsentStatus: function() {
                return {
                    technical: EasyLytics.getCookie('eslt-tech-cookies-consent') === 'true',
                    analytical: EasyLytics.getAnalyticalPreference(),
                    timestamp: EasyLytics.getCookie('eslt-consent-timestamp')
                };
            },
            
            // Force check and load GA4 if conditions are met
            checkAndLoadGA4: function() {
                return EasyLytics.checkAndLoadGA4();
            },
            
            // Debug information
            debug: function() {
                console.log('=== EasyLytics Debug Info ===');
                console.log('easyLyticsAjax:', typeof easyLyticsAjax !== 'undefined' ? easyLyticsAjax : 'undefined');
                console.log('GA4 ID configured:', typeof easyLyticsAjax !== 'undefined' ? easyLyticsAjax.ga4_id : 'N/A');
                console.log('Hide button text configured:', typeof easyLyticsAjax !== 'undefined' ? easyLyticsAjax.cookie_settings_hide_btn : 'N/A');
                console.log('Has consent:', EasyLytics.hasConsent());
                console.log('Analytical preference:', EasyLytics.getAnalyticalPreference());
                console.log('YouTube preference:', EasyLytics.getYouTubePreference());
                console.log('Technical cookies:', EasyLytics.getCookie('eslt-tech-cookies-consent'));
                console.log('Analytical cookies:', EasyLytics.getCookie('eslt-analytical-cookies'));
                console.log('YouTube cookies:', EasyLytics.getCookie('eslt-youtube-cookies'));
                console.log('General consent:', EasyLytics.getCookie('eslt-cookies-consent'));
                console.log('gtag function exists:', typeof window.gtag !== 'undefined');
                console.log('dataLayer exists:', typeof window.dataLayer !== 'undefined');
                console.log('dataLayer length:', window.dataLayer ? window.dataLayer.length : 'N/A');
                console.log('easyLyticsGA4Loaded:', window.easyLyticsGA4Loaded);
                console.log('Popup exists:', $('#eslt-cookie-popup').length > 0);
                console.log('Events bound (click test):', $._data(document, "events"));
                console.log('All cookies:', document.cookie);
                console.log('===========================');
                
                return {
                    hasConsent: EasyLytics.hasConsent(),
                    analyticalPreference: EasyLytics.getAnalyticalPreference(),
                    youtubePreference: EasyLytics.getYouTubePreference(),
                    ga4Id: typeof easyLyticsAjax !== 'undefined' ? easyLyticsAjax.ga4_id : null,
                    hideButtonText: typeof easyLyticsAjax !== 'undefined' ? easyLyticsAjax.cookie_settings_hide_btn : null,
                    gtagExists: typeof window.gtag !== 'undefined',
                    dataLayerExists: typeof window.dataLayer !== 'undefined',
                    dataLayerLength: window.dataLayer ? window.dataLayer.length : 0,
                    ga4Loaded: window.easyLyticsGA4Loaded,
                    popupExists: $('#eslt-cookie-popup').length > 0,
                    eventsCount: $._data ? Object.keys($._data(document, "events") || {}).length : 'N/A'
                };
            },
            
            // Force rebind events (useful for troubleshooting)
            rebindEvents: function() {
                EasyLytics.bindEvents();
                EasyLytics.handleKeyboardNavigation();
            },
            
            // Check settings section state (for debugging)
            checkSettingsState: function() {
                const $popup = $('#eslt-cookie-popup');
                const $settingsView = $popup.find('.eslt-settings-view');
                const state = {
                    popupExists: $popup.length > 0,
                    settingsExists: $settingsView.length > 0,
                    settingsVisible: $settingsView.is(':visible'),
                    settingsHidden: $settingsView.hasClass('eslt-hidden'),
                    settingsShow: $settingsView.hasClass('eslt-show'),
                    settingsClasses: $settingsView.attr('class'),
                    popupVisible: $popup.is(':visible'),
                    popupClasses: $popup.attr('class')
                };
                return state;
            },

            // Get YouTube preference
            getYouTubePreference: function() {
                return EasyLytics.getYouTubePreference();
            },

            // Update YouTube consent programmatically
            updateYouTubeConsent: function(consent) {
                EasyLytics.setCookie('eslt-youtube-cookies', consent ? 'true' : 'false', 7);
                if (consent) {
                    EasyLytics.unblockYouTubeVideos();
                } else {
                    // Reload to re-apply blocking
                    if (confirm('Page will reload to apply YouTube blocking. Continue?')) {
                        location.reload();
                    }
                }
            }
        }
    };
    
    // Make API available globally
    window.EasyLyticsAPI = EasyLytics.api;
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Always initialize event bindings
        EasyLytics.bindEvents();
        /*
        // Only show popup automatically if it exists and consent not already given
        if ($('#eslt-cookie-popup').length && !EasyLytics.hasConsent()) {
            EasyLytics.showPopup();
        }
        */
        // Only check and load GA4 if user has explicitly given analytical consent
        if (EasyLytics.getCookie('eslt-analytical-cookies') === 'true') {
            EasyLytics.checkAndLoadGA4();
        }
    });
    
    // Add method to check and load GA4
    EasyLytics.checkAndLoadGA4 = function() {
        // Only load GA4 if analytical cookies were explicitly accepted (not null/undefined)
        const analyticalCookie = this.getCookie('eslt-analytical-cookies');
        const hasExplicitConsent = analyticalCookie === 'true';
        
        if (hasExplicitConsent && typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.ga4_id) {
            // Check if GA4 is already loaded to prevent duplicate loading
            if (window.gtag && window.dataLayer && window.dataLayer.length > 0) {
                return true; // Already loaded
            }
            
            // Wait a moment for DOM to be ready
            setTimeout(() => {
                this.loadGA4();
            }, 100);
            
            return true;
        }
        
        return false;
    };
    
    // Handle page visibility changes (for multi-tab consent sync)
    $(document).on('visibilitychange', function() {
        if (document.hidden) return;
        
        // Check if consent was given in another tab
        if (EasyLytics.hasConsent() && $('#eslt-cookie-popup').is(':visible')) {
            EasyLytics.hidePopup();
        }
        
        // Check and load GA4 if analytical cookies were accepted in another tab
        if (EasyLytics.getCookie('eslt-analytical-cookies') === 'true' && typeof easyLyticsAjax !== 'undefined' && easyLyticsAjax.ga4_id) {
            if (!window.gtag || !window.dataLayer || !window.easyLyticsGA4Loaded) {
                EasyLytics.loadGA4();
            }
        }
    });
    
    // Handle browser back/forward navigation
    $(window).on('pageshow', function(event) {
        if (event.originalEvent.persisted) {
            // Page was restored from cache
            if (EasyLytics.hasConsent() && $('#eslt-cookie-popup').is(':visible')) {
                EasyLytics.hidePopup();
            }
        }
    });
    
})(jQuery);