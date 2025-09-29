<?php
/**
 * EasyLytics YouTube Class
 * 
 * Handles YouTube video blocking and consent management
 * 
 * @package EasyLytics
 * @version 1.4.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class EasyLytics_YouTube {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Constructor can be used for initialization if needed
    }
    
    /**
     * Process YouTube content based on consent
     */
    public function process_content($content) {
        // Skip blocking for bots
        if ($this->is_bot()) {
            return $content;
        }
        
        // Allow videos if user has consented
        if ($this->should_allow_youtube()) {
            return $content;
        }
        
        // Block YouTube videos by replacing iframes with placeholders
        $content = preg_replace_callback(
            '/(<iframe[^>]*)\ssrc=(["\'])([^"\']*(?:youtube\.com|youtube-nocookie\.com)\/embed\/([^"\'&]+)[^"\']*)\2([^>]*>.*?<\/iframe>)/is',
            array($this, 'create_youtube_placeholder'),
            $content
        );
        
        return $content;
    }
    
    /**
     * Filter block content for YouTube embeds
     */
    public function filter_block_content($block_content, $block) {
        if (strpos($block_content, 'youtube.com') !== false || 
            strpos($block_content, 'youtube-nocookie.com') !== false) {
            return $this->process_content($block_content);
        }
        return $block_content;
    }
    
    /**
     * Check if request is from a bot
     */
    private function is_bot() {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $bots = array(
            'googlebot',
            'bingbot',
            'slurp',
            'duckduckbot',
            'baiduspider',
            'yandexbot',
            'facebookexternalhit'
        );
        
        foreach ($bots as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if YouTube videos should be allowed
     */
    private function should_allow_youtube() {
        // Check if YouTube blocking is disabled in admin settings
        $disable_youtube = get_option('eslt_disable_youtube_cookies', '0');
        
        // If YouTube blocking is not enabled, allow all videos
        if ($disable_youtube !== '1') {
            return true;
        }
        
        // YouTube blocking is enabled - check for user consent
        if ($this->get_cookie_value('eslt-cookies-consent') === 'true' && 
            $this->get_cookie_value('eslt-youtube-cookies') === 'true') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get cookie value (server-side)
     */
    private function get_cookie_value($name) {
        if (!isset($_COOKIE[$name])) {
            return null;
        }
        return sanitize_text_field($_COOKIE[$name]);
    }
    
    /**
     * Create YouTube placeholder with overlay
     */
    private function create_youtube_placeholder($matches) {
        $iframe_start = $matches[1];
        $quote = $matches[2];
        $src_value = $matches[3];
        $video_id = $matches[4];
        $iframe_end = $matches[5];
        
        // Add blur class to iframe
        if (preg_match('/class=(["\'])([^"\']*)\1/i', $iframe_start, $class_matches)) {
            $existing_classes = $class_matches[2];
            $new_classes = trim($existing_classes . ' eslt-youtube-blurred');
            $iframe_start = preg_replace(
                '/class=(["\'])[^"\']*\1/i',
                'class=' . $class_matches[1] . $new_classes . $class_matches[1],
                $iframe_start
            );
        } else {
            $iframe_start .= ' class="eslt-youtube-blurred"';
        }
        
        // Build modified iframe with data-src instead of src
        $modified_iframe = $iframe_start . ' data-src=' . $quote . $src_value . $quote . $iframe_end;
        
        // Get custom text from options
        $blocked_title = get_option('eslt_youtube_blocked_title', 'YouTube Video Blocked');
        $blocked_message = get_option('eslt_youtube_blocked_message', 'This video requires YouTube cookies to play.');
        $accept_button = get_option('eslt_youtube_accept_button', 'Allow YouTube Cookies & Play');
        
        // Return iframe with overlay wrapper
        return $modified_iframe . sprintf(
            '<div class="eslt-youtube-wrapper" data-video-id="%s">
                <div class="eslt-youtube-overlay-container">
                    <div class="eslt-youtube-overlay">
                        <div class="eslt-youtube-play-button"></div>
                        <div class="eslt-youtube-consent-banner">
                            <h4>%s</h4>
                            <p>%s</p>
                            <button class="eslt-youtube-accept-btn" data-video-id="%s">
                                %s
                            </button>
                        </div>
                    </div>
                </div>
            </div>',
            esc_attr($video_id),
            esc_html($blocked_title),
            esc_html($blocked_message),
            esc_attr($video_id),
            esc_html($accept_button)
        );
    }
}