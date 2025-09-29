# EasyLytics - WordPress Cookie Consent Plugin

**Version:** 1.4.0  
**WordPress Compatibility:** 5.0+  
**PHP Version:** 7.4+  
**License:** GPL v2 or later

A GDPR-compliant WordPress plugin that provides cookie consent management with Google Analytics 4 integration, YouTube video blocking, multilingual support, and full customization options.

---

## 🌟 Key Features

### Privacy & Compliance
- **GDPR Compliant**: Full cookie consent management with granular controls
- **Privacy-First Design**: Users can decline analytics while maintaining essential functionality
- **YouTube Cookie Control**: Block YouTube videos until user consent is given
- **Cookie Categorization**: Technical (required), Analytical (optional), and YouTube (optional) cookie types
- **7-Day Cookie Duration**: Reasonable expiration period with secure cookie flags
- **Close Button**: Non-persistent close option - popup reappears until consent is given

### User Experience
- **Smooth Animations**: jQuery-powered slide animations with reduced motion support
- **Mobile Optimized**: Responsive design that centers on mobile devices
- **Keyboard Navigation**: Full accessibility support with focus management
- **Success Feedback**: Customizable confirmation messages after saving preferences

### Customization
- **Complete Color Control**: Professional default palette with full customization
- **Custom Content**: All popup text editable through admin interface
- **HTML Support**: Rich text formatting in descriptions with safe HTML tags
- **Multiple Positions**: Bottom-right, bottom-left, or bottom-center placement
- **Typography Settings**: Configurable font sizes (12-24px)

### Integration
- **Google Analytics 4**: Privacy-focused GA4 loading with consent-based activation
- **YouTube Video Blocking**: Blocks YouTube embeds until user consent (optional feature)
- **Conflict Detection**: Built-in scanner for other analytics implementations
- **Shortcode Support**: `[easylytics-btn]` for reopening consent popup
- **Event System**: JavaScript events for third-party integrations
- **Multi-tab Sync**: Consent status synchronized across browser tabs

---

## 📁 File Structure

```
easylytics/
├── easylytics.php                          # Main bootstrap file
├── uninstall.php                           # Clean uninstall script
├── README.md                               # This documentation
├── README.html                             # Documentation in HTML format
│
├── includes/                               # Plugin core classes
│   ├── class-easylytics-core.php          # Core orchestration class
│   ├── class-easylytics-admin.php         # Admin interface & settings
│   ├── class-easylytics-assets.php        # Scripts & styles management
│   ├── class-easylytics-frontend.php      # Frontend popup display
│   ├── class-easylytics-youtube.php       # YouTube blocking functionality
│   ├── admin-page-template.php            # Admin page HTML template
│   │
│   └── admin-tabs/                        # Admin interface tabs
│       ├── tab-general.php                # General settings
│       ├── tab-content.php                # Content customization
│       ├── tab-appearance.php             # Color & styling options
│       ├── tab-tools.php                  # Tools & utilities
│       └── tab-description.php            # Documentation display
│
├── assets/                                 # Frontend & admin assets
│   ├── css/
│   │   ├── easylytics.css                 # Frontend styles
│   │   └── easylytics-admin.css           # Admin interface styles
│   │
│   └── js/
│       ├── easylytics.js                  # Frontend functionality
│       └── easylytics-admin.js            # Admin interface scripts
│
└── languages/                              # Translation files
    ├── easylytics.pot                     # Translation template
    ├── easylytics-en_US.po                # English translation
    ├── easylytics-en_US.mo                # English compiled
    ├── easylytics-sk_SK.po                # Slovak translation
    └── easylytics-sk_SK.mo                # Slovak compiled
```

---

## 🏗️ Architecture

### Modular Design (v1.4.0)

The plugin follows a modern, modular architecture with separated concerns:

#### Core Classes

**`EasyLytics_Core`** - Main orchestrator
- Initializes all plugin components
- Registers WordPress hooks
- Manages plugin lifecycle
- Provides default settings

**`EasyLytics_Admin`** - Admin interface
- Settings page rendering
- AJAX request handling
- Conflict scanner
- Settings save/reset logic

**`EasyLytics_Assets`** - Asset management
- Frontend script/style enqueuing
- Admin script/style enqueuing
- Dynamic CSS generation
- File versioning

**`EasyLytics_Frontend`** - Frontend display
- Cookie popup rendering
- Shortcode handling
- User consent interface

**`EasyLytics_YouTube`** - YouTube blocking
- Content filtering
- Bot detection for SEO
- Video placeholder creation
- Consent verification

#### Benefits of Modular Structure

✅ **Maintainability** - Each class has a single responsibility  
✅ **Scalability** - Easy to add new features without modifying core  
✅ **Testability** - Components can be tested independently  
✅ **Performance** - Autoloader only loads needed classes  
✅ **Code Organization** - Logical separation makes debugging easier

---

## 🚀 Installation

### Method 1: WordPress Admin

1. Download the plugin ZIP file
2. Go to **Plugins → Add New → Upload Plugin**
3. Choose the ZIP file and click **Install Now**
4. Click **Activate Plugin**

### Method 2: Manual Installation

1. Upload the `easylytics` folder to `/wp-content/plugins/`
2. Go to **Plugins** in WordPress admin
3. Click **Activate** for EasyLytics

### Method 3: Git Clone

```bash
cd /path/to/wp-content/plugins/
git clone https://github.com/wiliak16/easylytics.git
```

Then activate via WordPress admin.

---

## ⚙️ Configuration

### Initial Setup

1. **Install & Activate** the plugin
2. Go to **Settings → EasyLytics**
3. Enter your **GA4 Measurement ID** (G-XXXXXXXXXX)
4. Optionally enable **YouTube video blocking**
5. Customize appearance and content as needed

### Admin Interface Tabs

#### 1️⃣ General Settings
- **GA4 Measurement ID**: Your Google Analytics 4 tracking code
- **Popup Position**: Choose from bottom-right, left, or center
- **YouTube Video Blocking**: Enable to block YouTube videos until user consent
- **Cookie Information**: Optional link to detailed cookie policy page

#### 2️⃣ Content Settings
- **Popup Title & Description**: Main consent message with HTML support
- **Button Labels**: Customize all button text including "Hide Settings"
- **Cookie Categories**: Descriptions for technical, analytical, and YouTube cookies
- **YouTube Blocking Messages**: Customize title, message, and button text for blocked videos
- **Success Message**: Confirmation text after saving preferences
- **Reset Function**: Restore all texts to English defaults

#### 3️⃣ Appearance Settings
- **Color Palette**: Background, text, button, and border colors
- **Typography**: Font size configuration (12-24px)
- **Reset Options**: Restore default professional color scheme

#### 4️⃣ Tools
- **Conflict Scanner**: Detect other Google Analytics implementations
- **Export/Import**: Backup and restore plugin settings
- **Advanced Options**: Future feature placeholders

#### 5️⃣ Description
- **Built-in Documentation**: Complete plugin information and usage guide

---

## 🎨 Default Color Scheme

| Element | Color | Hex Code |
|---------|-------|----------|
| Background | White | `#ffffff` |
| Text | Dark Gray | `#374151` |
| Primary Button (Accept All) | Blue | `#3b82f6` |
| Secondary Button (Settings) | Black | `#000000` |
| Tertiary Button (Reject All) | Red | `#dc2626` |
| Border | Light Gray | `#d1d5db` |

All colors are fully customizable through the Appearance tab.

---

## 🍪 Cookie Behavior

### User Actions & Cookie Storage

| User Action | Technical Cookies | Analytical Cookies | YouTube Cookies | GA4 Loading | Popup Behavior |
|-------------|-------------------|--------------------|-----------------| ------------|----------------|
| Close (✖) | No cookies saved | No cookies saved | No cookies saved | No | Reappears on reload |
| Accept All | Enabled | Enabled | Enabled* | Yes | Hidden permanently |
| Reject All | Enabled | Disabled | Disabled | No | Hidden permanently |
| Custom Settings | Enabled | User Choice | User Choice* | Conditional | Hidden permanently |

*Only when YouTube blocking is enabled in admin

### Cookie Details

- **Duration**: 7 days
- **Cookie Names**: 
  - `eslt-cookies-consent` (main consent)
  - `eslt-tech-cookies-consent` (technical - always true)
  - `eslt-analytical-cookies` (GA4 tracking)
  - `eslt-youtube-cookies` (YouTube embeds)
- **Security**: Secure, SameSite=Lax flags
- **Scope**: Site-wide, path=/

---

## 🎬 YouTube Video Blocking

### How It Works

When enabled in admin settings, YouTube videos are blocked until users give explicit consent:

1. **Admin enables blocking**: Check "Disable YouTube Cookies" in General settings
2. **Videos are blocked**: YouTube embeds show a consent overlay instead of playing
3. **User consent**: Users can accept YouTube cookies from the popup or directly on blocked videos
4. **Videos unblock**: After consent, all YouTube videos load normally

### Supported Embed Types

- WordPress auto-embeds (`[embed]https://youtube.com/watch?v=...`)
- Manual iframe embeds
- Gutenberg block embeds
- Widget embeds

### Customizable Messages

All blocking messages are customizable in the Content tab:
- YouTube Blocked Title
- YouTube Blocked Message
- YouTube Accept Button text

---

## 📝 Shortcode Usage

### Basic Shortcode

```php
[easylytics-btn]
```

### With Custom Parameters

```php
[easylytics-btn text="Manage Cookies" class="my-class" style="background: #ff6b35;"]
```

**Parameters:**
- `text` - Button text (default: "Cookie Settings")
- `class` - Custom CSS class
- `style` - Inline CSS styles

**Features:**
- Shows current cookie settings when reopened
- Fully accessible with keyboard navigation
- Customizable text, CSS class, and inline styles

---

## 🔌 JavaScript API

### Event Listeners

```javascript
// Listen for consent events
$(document).on('easylytics:consentGiven', function(e, data) {
    console.log('Consent given:', data.technical, data.analytical, data.youtube);
});

// Listen for YouTube consent
$(document).on('easylytics:youtubeConsentGiven', function(e, data) {
    console.log('YouTube consent for video:', data.videoId);
});
```

### API Methods

```javascript
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

---

## 🎣 WordPress Hooks

### Filters

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

---

## 🤖 Bot Detection & SEO

### How It Works

EasyLytics automatically detects search engine bots and shows them unblocked content for optimal SEO performance.

**Detected Bots:**
- Googlebot (Google)
- Bingbot (Microsoft Bing)
- Slurp (Yahoo)
- DuckDuckBot (DuckDuckGo)
- Baiduspider (Baidu)
- YandexBot (Yandex)
- FacebookExternalHit (Facebook)

### YouTube & SEO

When YouTube blocking is enabled:
- **Users see**: Consent overlay and blocked videos (GDPR compliant)
- **Bots see**: Normal YouTube embeds with full functionality
- **Result**: Full video indexing without compromising user privacy

**What This Means:**
✅ Video rich snippets appear in search results  
✅ Videos indexed in Google Video Search  
✅ Schema.org VideoObject data accessible  
✅ Video sitemaps work correctly  
✅ No negative SEO impact from blocking

**How It Works:**
1. Plugin detects request from search bot via User-Agent
2. Bot requests bypass YouTube blocking entirely
3. Bots receive original HTML with proper iframe src attributes
4. Regular users continue to see consent flow

> **Important**: This is NOT cloaking - it's adaptive content delivery based on client capabilities (bots cannot execute JavaScript or provide consent).

---

## 🔒 Privacy & Analytics

### Google Analytics 4 Configuration

When users accept analytical cookies, GA4 is loaded with privacy-enhanced settings:

- ✅ IP anonymization enabled
- ✅ Google Signals disabled
- ✅ Ad personalization disabled
- ✅ 7-day cookie expiration
- ✅ Secure cookie flags

> **Important**: Traffic data is still collected for website analytics, but with enhanced privacy protection. You'll receive valuable insights about page views, user sessions, referral sources, and general geographic data (country/region level), while user IP addresses are anonymized, cross-device tracking is disabled, and data retention is limited to 7 days instead of the default 2 years.

---

## 🌍 Translation & Localization

### Included Languages

- **English (en_US)**: Complete translation
- **Slovak (sk_SK)**: Complete translation
- **Translation Template**: `.pot` file for additional languages

### Adding New Languages

1. Copy `easylytics.pot` to `easylytics-{locale}.po`
2. Translate strings using Poedit or similar tool
3. Compile to `.mo` file using `msgfmt`
4. Place in `/languages/` directory

```bash
# Example: Creating German translation
cd languages/
cp easylytics.pot easylytics-de_DE.po
# Edit in Poedit
msgfmt easylytics-de_DE.po -o easylytics-de_DE.mo
```

---

## ⚡ Performance

- **Lightweight**: < 50KB total plugin size
- **Optimized Loading**: Scripts load only when needed
- **Cache Friendly**: Compatible with WordPress caching plugins
- **Mobile Optimized**: Responsive design for all devices
- **Reduced Motion**: Respects user accessibility preferences

### Browser Support

- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+
- Mobile browsers with ES6 support

---

## 🐛 Troubleshooting

### Popup Not Showing

- Verify plugin is activated
- Check if consent cookie already exists
- Clear browser cache and test in incognito mode

### GA4 Not Loading

```javascript
// Open browser console:
EasyLyticsAPI.debug();

// Verify GA4 ID format: G-XXXXXXXXXX

// Check analytical cookies accepted:
EasyLyticsAPI.getAnalyticalPreference();
```

### YouTube Videos Not Blocking

- Verify "Disable YouTube Cookies" is checked in admin
- Check browser console for JavaScript errors
- Confirm videos are standard WordPress embeds or iframes

### YouTube Videos Not Unblocking

```javascript
// Open browser console:
EasyLyticsAPI.getYouTubePreference();

// Clear browser cookies and test again

// Check if page reload is required
```

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

---

## 📋 System Requirements

- **PHP**: 7.4 or higher
- **WordPress**: 5.0 or higher
- **MySQL**: 5.6 or higher
- **JavaScript**: ES6 support required
- **jQuery**: Included with WordPress
- **CSS**: Modern browser support for CSS variables

---

## 🔐 Security Features

- **Nonce Verification**: All AJAX requests protected
- **Capability Checks**: Admin functions require proper permissions
- **Input Sanitization**: All user inputs properly sanitized
- **XSS Prevention**: Safe HTML rendering with `wp_kses()`
- **CSRF Protection**: WordPress nonce system implementation
- **Direct Access Prevention**: All PHP files check for `ABSPATH`
- **Prefixed Classes**: All CSS/JS classes use `eslt-` prefix to avoid conflicts

---

## 📅 Changelog

### Version 1.4.0 (Current)
- ✨ **Refactored to modular architecture** - Separated into logical class components
- ✨ Added YouTube video blocking feature
- ✨ Added customizable YouTube blocking messages
- ✨ Added bot detection for SEO optimization
- ✨ Improved JavaScript logic for conditional feature handling
- ✨ Enhanced admin interface with YouTube options
- ✨ Added `.eslt-` prefix to all notice classes to avoid WordPress conflicts
- 🐛 Fixed CSS class conflicts with WordPress core
- 🐛 Fixed button layout issues in cookie popup
- 📝 Updated translations for YouTube features
- ⚡ Performance improvements and code optimization

### Version 1.3.0
- Added tertiary button (Reject All)
- Enhanced color customization
- Improved accessibility features

### Version 1.2.0
- Added multilingual support
- Improved mobile responsiveness
- Added shortcode support

### Version 1.1.0
- Initial public release
- GDPR compliance features
- GA4 integration

---

## 🤝 Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Follow WordPress coding standards
4. Add translations for new strings
5. Test thoroughly across browsers
6. Submit a pull request

### Development Setup

```bash
# Clone repository
git clone https://github.com/wiliak16/easylytics.git
cd easylytics

# Install development dependencies (if any)
npm install

# Compile translations
msgfmt languages/easylytics-en_US.po -o languages/easylytics-en_US.mo
```

---

## 📄 License

This plugin is licensed under the **GPL v2 or later**.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

## 💬 Support

- **Documentation**: This README file
- **Issues**: [Report bugs via GitHub](https://github.com/wiliak16/easylytics/issues)
- **WordPress Forum**: Plugin support forum
- **Website**: [wiliak.sk](https://wiliak.sk)

---

## 👨‍💻 Author

**wiliak.sk**  
Website: [https://wiliak.sk](https://wiliak.sk)

---

**EasyLytics** - Simple, compliant, effective cookie consent management for WordPress.

Made with ❤️ for the WordPress community.