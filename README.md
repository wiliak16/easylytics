# EasyLytics - WordPress Cookie Consent Plugin

**Version:** 1.4.0  
**WordPress Compatibility:** 5.0+  
**PHP Version:** 7.4+  
**License:** GPL v2 or later

A GDPR-compliant WordPress plugin that provides cookie consent management with Google Analytics 4 integration, YouTube video blocking, multilingual support, and full customization options.

## Key Features

### Privacy & Compliance
- **GDPR Compliant**: Full cookie consent management with granular controls
- **Privacy-First Design**: Users can decline analytics while maintaining essential functionality
- **YouTube Cookie Control**: Block YouTube videos until user consent is given
- **Cookie Categorization**: Technical (required), Analytical (optional), and YouTube (optional) cookie types
- **7-Day Cookie Duration**: Reasonable expiration period with secure cookie flags

### User Experience
- **Close Button**: Non-persistent close option - popup reappears until consent is given
- **Smooth Animations**: jQuery-powered slide animations with reduced motion support
- **Mobile Optimized**: Responsive design that centers on mobile devices
- **Keyboard Navigation**: Full accessibility support with focus management
- **Success Feedback**: Customizable confirmation messages after saving preferences

### Customization Options
- **Complete Color Control**: Professional default palette with full customization
- **Custom Content**: All popup text editable through admin interface
- **HTML Support**: Rich text formatting in descriptions with safe HTML tags
- **Multiple Positions**: Bottom-right, bottom-left, or bottom-center placement
- **Typography Settings**: Configurable font sizes (12-24px)

### Technical Integration
- **Google Analytics 4**: Privacy-focused GA4 loading with consent-based activation
- **YouTube Video Blocking**: Blocks YouTube embeds until user consent (optional feature)
- **Conflict Detection**: Built-in scanner for other analytics implementations
- **Shortcode Support**: `[easylytics-btn]` for reopening consent popup
- **Event System**: JavaScript events for third-party integrations
- **Multi-tab Sync**: Consent status synchronized across browser tabs
- [Bot Detection & SEO](#bot-detection--seo)

## File Structure

```
easylytics/
├── easylytics.php                 # Main plugin file
├── uninstall.php                  # Clean uninstall script
├── README.md                      # This documentation
├── README.html                    # This documentation in html format
├── assets/
│   ├── css/
│   │   ├── easylytics.css         # Frontend styles
│   │   └── easylytics-admin.css   # Admin interface styles
│   └── js/
│       ├── easylytics.js          # Frontend functionality
│       └── easylytics-admin.js    # Admin interface scripts
└── languages/
    ├── easylytics.pot             # Translation template
    ├── easylytics-en_US.po        # English translation
    ├── easylytics-en_US.mo        # English compiled
    ├── easylytics-sk_SK.po        # Slovak translation
    └── easylytics-sk_SK.mo        # Slovak compiled
```

## Quick Setup

1. **Install the Plugin**
   - Upload to `/wp-content/plugins/easylytics/`
   - Or install via WordPress admin

2. **Activate & Configure**
   - Go to Settings → EasyLytics
   - Enter your GA4 Measurement ID (G-XXXXXXXXXX)
   - Optionally enable YouTube video blocking
   - Customize appearance and content as needed

3. **Test Integration**
   - View your site in incognito mode
   - Verify cookie popup appears
   - Test consent flow and GA4 loading
   - Test YouTube blocking if enabled

## Admin Interface

### General Tab
- **GA4 Measurement ID**: Your Google Analytics 4 tracking code
- **Popup Position**: Choose from bottom-right, left, or center
- **YouTube Video Blocking**: Enable to block YouTube videos until user consent
- **Cookie Information**: Optional link to detailed cookie policy page

### Content Tab
- **Popup Title & Description**: Main consent message with HTML support
- **Button Labels**: Customize all button text including "Hide Settings"
- **Cookie Categories**: Descriptions for technical, analytical, and YouTube cookies
- **YouTube Blocking Messages**: Customize title, message, and button text for blocked videos
- **Success Message**: Confirmation text after saving preferences
- **Reset Function**: Restore all texts to English defaults

### Appearance Tab
- **Color Palette**: Background, text, button, and border colors
- **Typography**: Font size configuration (12-24px)
- **Reset Options**: Restore default professional color scheme

### Tools Tab
- **Conflict Scanner**: Detect other Google Analytics implementations
- **Export/Import**: Backup and restore plugin settings

### Description Tab
- **Built-in Documentation**: Complete plugin information and usage guide

## Default Color Scheme

| Element | Color | Hex Code |
|---------|-------|----------|
| Background | White | `#ffffff` |
| Text | Dark Gray | `#374151` |
| Primary Button (Accept All) | Blue | `#3b82f6` |
| Secondary Button (Settings) | Black | `#000000` |
| Tertiary Button (Reject All) | Red | `#dc2626` |
| Border | Light Gray | `#d1d5db` |

## Cookie Consent Behavior

| User Action | Technical Cookies | Analytical Cookies | YouTube Cookies | GA4 Loading | Popup Behavior |
|-------------|-------------------|-------------------|-----------------|-------------|----------------|
| **Close (✖)** | No cookies saved | No cookies saved | No cookies saved | No | Reappears on reload |
| **Accept All** | Enabled | Enabled | Enabled* | Yes | Hidden permanently |
| **Custom Settings** | Enabled | User Choice | User Choice* | Conditional | Hidden permanently |

*Only when YouTube blocking is enabled in admin

### Cookie Details
- **Duration**: 7 days
- **Names**: `eslt-cookies-consent`, `eslt-tech-cookies-consent`, `eslt-analytical-cookies`, `eslt-youtube-cookies`
- **Security**: Secure, SameSite=Lax flags

## YouTube Video Blocking

When enabled in admin settings, YouTube videos are blocked until users give explicit consent:

### How It Works
1. **Admin enables blocking**: Check "Disable YouTube Cookies" in General settings
2. **Videos are blocked**: YouTube embeds show a consent overlay instead of playing
3. **User consent**: Users can accept YouTube cookies from the popup or directly on blocked videos
4. **Videos unblock**: After consent, all YouTube videos load normally

### What Gets Blocked
- WordPress auto-embeds (`[embed]https://youtube.com/watch?v=...`)
- Manual iframe embeds
- Block editor embeds
- Widget embeds

### Customization
All blocking messages are customizable in the Content tab:
- YouTube Blocked Title
- YouTube Blocked Message  
- YouTube Accept Button text

## Shortcode Usage

### Basic Cookie Settings Button
```html
[easylytics-btn]
```

### Custom Button
```html
[easylytics-btn text="Manage Cookies" class="my-class" style="background: #ff6b35;"]
```

**Features:**
- Shows current cookie settings when reopened
- Fully accessible with keyboard navigation
- Customizable text, CSS class, and inline styles

## Developer API

### JavaScript Events
```javascript
// Listen for consent events
$(document).on('easylytics:consentGiven', function(e, data) {
    console.log('Consent given:', data.technical, data.analytical, data.youtube);
});

// Listen for YouTube consent
$(document).on('easylytics:youtubeConsentGiven', function(e, data) {
    console.log('YouTube consent for video:', data.videoId);
});

// Check consent status
if (window.EasyLyticsAPI.hasConsent()) {
    console.log('User has given consent');
}

// Check YouTube consent
if (window.EasyLyticsAPI.getYouTubePreference()) {
    console.log('YouTube cookies accepted');
}

// Show popup programmatically
window.EasyLyticsAPI.showPopup();

// Get detailed debug information
window.EasyLyticsAPI.debug();
```

### PHP Hooks
```php
// Modify popup content
add_filter('easylytics_popup_content', function($content) {
    return $content . '<p>Custom message</p>';
});

// Override default colors
add_filter('easylytics_default_colors', function($colors) {
    $colors['eslt_primary_btn_bg'] = '#ff6b35';
    return $colors;
});
```

## Privacy Features

### YouTube Cookie Prevention
When enabled, automatically blocks YouTube embeds until user consent:
```html
<!-- Blocked State -->
<iframe data-src="https://youtube.com/embed/VIDEO_ID" class="eslt-youtube-blurred"></iframe>
<div class="eslt-youtube-wrapper">
    <!-- Consent overlay -->
</div>

<!-- After Consent -->
<iframe src="https://youtube.com/embed/VIDEO_ID"></iframe>
```

### GA4 Privacy Configuration
- IP anonymization enabled
- Google Signals disabled
- Ad personalization disabled
- 7-day cookie expiration
- Secure cookie flags

**Important**: Traffic data is still collected for website analytics, but with enhanced privacy protection. You'll receive valuable insights about page views, user sessions, referral sources, and general geographic data (country/region level), while user IP addresses are anonymized, cross-device tracking is disabled, and data retention is limited to 7 days instead of the default 2 years.

## Bot Detection & SEO

### Automatic Bot Detection

EasyLytics automatically detects search engine bots and shows them unblocked content for optimal SEO performance.

**Detected Bots:**
- Googlebot (Google)
- Bingbot (Microsoft Bing)
- Slurp (Yahoo)
- DuckDuckBot (DuckDuckGo)
- Baiduspider (Baidu)
- YandexBot (Yandex)
- FacebookExternalHit (Facebook)

### SEO Benefits

When YouTube blocking is enabled:
- **Users see**: Consent overlay and blocked videos (GDPR compliant)
- **Bots see**: Normal YouTube embeds with full functionality
- **Result**: Full video indexing without compromising user privacy

**What This Means:**
- ✅ Video rich snippets appear in search results
- ✅ Videos indexed in Google Video Search
- ✅ Schema.org VideoObject data accessible
- ✅ Video sitemaps work correctly
- ✅ No negative SEO impact from blocking

### How It Works

1. Plugin detects request from search bot via User-Agent
2. Bot requests bypass YouTube blocking entirely
3. Bots receive original HTML with proper iframe src attributes
4. Regular users continue to see consent flow

**Important**: This is NOT cloaking - it's adaptive content delivery based on client capabilities (bots cannot execute JavaScript or provide consent).

## Translation Support

The plugin includes:
- **English (en_US)**: Complete translation
- **Slovak (sk_SK)**: Complete translation
- **Translation Template**: `.pot` file for additional languages

### Adding New Languages
1. Copy `easylytics.pot` to `easylytics-{locale}.po`
2. Translate strings using Poedit or similar tool
3. Compile to `.mo` file using `msgfmt`
4. Place in `/languages/` directory

## Performance Features

- **Lightweight**: < 50KB total plugin size
- **Optimized Loading**: Scripts load only when needed
- **Cache Friendly**: Compatible with WordPress caching plugins
- **Mobile Optimized**: Responsive design for all devices
- **Reduced Motion**: Respects user accessibility preferences

## Browser Support

- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+
- Mobile browsers with ES6 support

## Troubleshooting

### Common Issues

**Popup Not Showing**
- Verify plugin is activated
- Check if consent cookie already exists
- Clear browser cache and test in incognito mode

**GA4 Not Loading**
- Open browser console: `EasyLyticsAPI.debug()`
- Verify GA4 ID format: `G-XXXXXXXXXX`
- Check analytical cookies accepted: `EasyLyticsAPI.getAnalyticalPreference()`

**YouTube Videos Not Blocking**
- Verify "Disable YouTube Cookies" is checked in admin
- Check browser console for JavaScript errors
- Confirm videos are standard WordPress embeds or iframes

**YouTube Videos Not Unblocking**
- Open browser console: `EasyLyticsAPI.getYouTubePreference()`
- Clear browser cookies and test again
- Check if page reload is required

### Debug Commands
```javascript
// Full debug information
EasyLyticsAPI.debug();

// Check popup state
EasyLyticsAPI.checkSettingsState();

// Check YouTube consent
EasyLyticsAPI.getYouTubePreference();

// Manual GA4 loading
EasyLyticsAPI.checkAndLoadGA4();

// Rebind all events
EasyLyticsAPI.rebindEvents();
```

## Requirements

### Server Requirements
- **PHP**: 7.4 or higher
- **WordPress**: 5.0 or higher
- **MySQL**: 5.6 or higher

### Browser Requirements
- **JavaScript**: ES6 support required
- **jQuery**: Included with WordPress
- **CSS**: Modern browser support for CSS variables

## Security Features

- **Nonce Verification**: All AJAX requests protected
- **Capability Checks**: Admin functions require proper permissions
- **Input Sanitization**: All user inputs properly sanitized
- **XSS Prevention**: Safe HTML rendering with wp_kses
- **CSRF Protection**: WordPress nonce system implementation

## Changelog

### Version 1.2.0
- Added YouTube video blocking feature
- Added customizable YouTube blocking messages
- Improved JavaScript logic for conditional feature handling
- Enhanced admin interface with YouTube options
- Updated translations for YouTube features
- Bug fixes and performance improvements

## Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch
3. Follow WordPress coding standards
4. Add translations for new strings
5. Test thoroughly across browsers
6. Submit a pull request

### Development Setup
```bash
# Clone repository
git clone https://github.com/your-repo/easylytics

# Install development dependencies
npm install

# Compile translations
msgfmt languages/easylytics-en_US.po -o languages/easylytics-en_US.mo
```

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## Support

- **Documentation**: This README file
- **Issues**: Report bugs via GitHub issues
- **WordPress Forum**: Plugin support forum
- **Community**: Join our developer community

---

**EasyLytics - Simple, compliant, effective cookie consent management for WordPress.**