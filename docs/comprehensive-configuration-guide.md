# Comprehensive Configuration Guide

This comprehensive guide covers all configuration options available in the Filament PWA plugin, including the new internationalization features and advanced configuration patterns.

## Table of Contents

- [Configuration Methods](#configuration-methods)
- [Basic Configuration](#basic-configuration)
- [Advanced Configuration](#advanced-configuration)
- [Internationalization](#internationalization)
- [Configuration File Reference](#configuration-file-reference)
- [Environment Variables](#environment-variables)
- [Smart Defaults](#smart-defaults)
- [Validation](#validation)

## Configuration Methods

The plugin supports multiple configuration approaches with different priorities:

1. **Fluent API** (Recommended) - Configure directly in your panel provider
2. **Configuration File** - Use the published config file
3. **Environment Variables** - Override settings via .env file
4. **Dynamic Configuration** - Use closures for runtime configuration

### Configuration Priority

The plugin merges configuration from multiple sources in this order (highest to lowest priority):

1. **Fluent API configuration** - Direct method calls on the plugin
2. **Configuration file** - `config/filament-pwa.php`
3. **Environment variables** - `.env` file
4. **Default values** - Built-in defaults with smart detection

## Basic Configuration

### App Information

Configure the basic information about your PWA:

```php
FilamentPwaPlugin::make()
    ->name('My Admin Panel')                    // Full app name
    ->shortName('Admin')                        // Short name for home screen
    ->description('Powerful admin panel for managing your application')
    ->startUrl('/admin')                        // URL when app opens
```

### Display Settings

Control how your PWA appears when installed:

```php
FilamentPwaPlugin::make()
    ->displayMode('standalone')                 // Display mode
    ->orientation('portrait')                   // Screen orientation
    ->scope('/admin')                          // Navigation scope
    
    // Convenience methods
    ->standalone()                             // Same as displayMode('standalone')
    ->fullscreen()                             // Same as displayMode('fullscreen')
    ->portrait()                               // Same as orientation('portrait')
    ->landscape()                              // Same as orientation('landscape')
```

**Available Display Modes:**
- `standalone` - Looks like a native app (recommended)
- `fullscreen` - Full screen without browser UI
- `minimal-ui` - Minimal browser UI
- `browser` - Regular browser tab

**Available Orientations:**
- `portrait` - Portrait orientation preferred
- `landscape` - Landscape orientation preferred
- `portrait-primary` - Primary portrait orientation
- `landscape-primary` - Primary landscape orientation
- `any` - Any orientation allowed

### Theme Configuration

Customize the visual appearance:

```php
FilamentPwaPlugin::make()
    ->themeColor('#3B82F6')                    // Browser UI color
    ->backgroundColor('#ffffff')                // Loading screen background
```

## Advanced Configuration

### Dynamic Configuration with Closures

Use closures for runtime configuration based on user preferences or environment:

```php
FilamentPwaPlugin::make()
    // User-specific configuration
    ->name(fn() => auth()->user()?->company_name ?? 'Admin Panel')
    ->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())
    ->direction(fn() => auth()->user()?->text_direction ?? 'ltr')
    
    // Environment-based configuration
    ->name(fn() => app()->environment('production') ? 'Production Admin' : 'Dev Admin')
    ->themeColor(fn() => app()->environment('production') ? '#dc2626' : '#3B82F6')
    
    // Complex dynamic shortcuts
    ->addShortcut(fn() => [
        'name' => 'My Dashboard',
        'url' => '/admin/dashboard/' . auth()->id(),
        'description' => 'Personal dashboard for ' . auth()->user()?->name,
        'icons' => [
            [
                'src' => auth()->user()?->avatar_url ?? '/images/default-avatar.png',
                'sizes' => '96x96',
            ],
        ],
    ])
```

### Installation Prompts Configuration

Control when and how installation prompts appear:

```php
FilamentPwaPlugin::make()
    ->enableInstallation(2000)                 // Show prompt after 2 seconds
    ->disableInstallation()                    // Disable prompts entirely
    
    // Full installation configuration
    ->installation(
        enabled: true,                         // Enable installation prompts
        promptDelay: 2000,                     // Delay before showing prompt (ms)
        iosInstructionsDelay: 5000,            // Delay for iOS instructions (ms)
        showBannerInDebug: true                // Always show in debug mode
    )
    
    // Debug mode helpers
    ->enableDebugBanner()                      // Always show banner in debug
    ->disableDebugBanner()                     // Disable debug banner
```

### Service Worker Configuration

Configure caching strategies and offline behavior:

```php
FilamentPwaPlugin::make()
    ->serviceWorker(
        cacheName: 'my-app-v1.0.0',           // Cache name for versioning
        offlineUrl: '/offline',                // Offline fallback page
        cacheUrls: [                           // URLs to cache immediately
            '/admin',
            '/admin/login',
            '/admin/dashboard',
            '/manifest.json'
        ]
    )
```

### Icon Configuration

Configure icon generation and paths:

```php
FilamentPwaPlugin::make()
    ->icons(
        path: 'images/icons',                  // Output directory
        sizes: [72, 96, 128, 144, 152, 192, 384, 512], // Standard sizes
        maskableSizes: [192, 512]              // Maskable icon sizes
    )
```

### App Shortcuts

Add shortcuts that appear when users long-press your app icon:

```php
FilamentPwaPlugin::make()
    ->addShortcut('Dashboard', '/admin', 'Main dashboard')
    ->addShortcut('Users', '/admin/users', 'Manage users')
    ->addShortcut('Settings', '/admin/settings', 'App settings')
    
    // With custom icons
    ->addShortcut(
        name: 'Reports',
        url: '/admin/reports',
        description: 'View reports',
        icons: [
            [
                'src' => '/images/icons/reports-icon.png',
                'sizes' => '96x96',
            ],
        ]
    )
    
    // Dynamic shortcuts with closures
    ->addShortcut(fn() => [
        'name' => 'My Profile',
        'url' => '/admin/profile/' . auth()->id(),
        'description' => 'View my profile',
    ])
```

### Categories Configuration

Define app categories for app stores:

```php
FilamentPwaPlugin::make()
    ->categories(['productivity', 'business', 'utilities'])
```

Common categories: `productivity`, `business`, `utilities`, `lifestyle`, `social`, `entertainment`, `education`, `health`, `finance`, `travel`

## Internationalization

The plugin includes comprehensive internationalization support with built-in translations for 10+ languages.

### Automatic Language Detection

The plugin automatically detects language and text direction:

```php
FilamentPwaPlugin::make()
    // Language and direction are auto-detected from Laravel's locale
    // No additional configuration needed
```

### Manual Language Configuration

Override automatic detection:

```php
FilamentPwaPlugin::make()
    ->language('ar')                           // Set specific language
    ->direction('rtl')                         // Set text direction
    
    // Convenience methods
    ->ltr()                                    // Set left-to-right
    ->rtl()                                    // Set right-to-left
```

### Dynamic Language Configuration

Configure language based on user preferences:

```php
FilamentPwaPlugin::make()
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())
    ->direction(fn() => auth()->user()?->text_direction ?? 'ltr')
```

### Supported Languages

| Language | Code | Direction | Status |
|----------|------|-----------|--------|
| English | `en` | LTR | ✅ Complete |
| Arabic | `ar` | RTL | ✅ Complete |
| Spanish | `es` | LTR | ✅ Complete |
| French | `fr` | LTR | ✅ Complete |
| German | `de` | LTR | ✅ Complete |
| Portuguese | `pt` | LTR | ✅ Complete |
| Italian | `it` | LTR | ✅ Complete |
| Russian | `ru` | LTR | ✅ Complete |
| Japanese | `ja` | LTR | ✅ Complete |
| Chinese (Simplified) | `zh-CN` | LTR | ✅ Complete |
| Dutch | `nl` | LTR | ✅ Complete |

### RTL Language Support

For RTL languages, the plugin automatically:
- Sets `dir="rtl"` in the PWA manifest
- Provides culturally appropriate translations
- Handles text direction in installation prompts

```php
// Automatic RTL detection for Arabic
app()->setLocale('ar');

FilamentPwaPlugin::make()
    // Automatically detects Arabic and sets RTL direction
```

### Custom Translations

To customize translations:

1. **Publish language files:**
   ```bash
   php artisan vendor:publish --tag="filament-pwa-lang"
   ```

2. **Modify translations in `resources/lang/{locale}/pwa.php`:**
   ```php
   // resources/lang/ar/pwa.php
   return [
       'install_title' => 'تثبيت التطبيق',
       'install_description' => 'احصل على تجربة أفضل مع التطبيق المثبت',
       // ... more translations
   ];
   ```

### Translation Keys Reference

The plugin uses the following translation keys:

```php
// Installation prompts
'install_title', 'install_description', 'install_button', 'dismiss_button'

// iOS installation
'ios_install_title', 'ios_install_description', 'ios_step_1', 'ios_step_2', 'ios_step_3', 'got_it'

// Updates
'update_available', 'update_description', 'update_now', 'update_later'

// Offline functionality
'offline_title', 'offline_subtitle', 'offline_status', 'online_status'

// Features and actions
'available_features', 'retry_connection', 'go_home'

// Validation messages
'validation.manifest_missing', 'validation.service_worker_missing'

// Setup command messages
'setup.starting', 'setup.completed', 'setup.validation_passed'
```

## Configuration File Reference

When using the configuration file approach, all options are available in `config/filament-pwa.php`:

```php
return [
    // App Information
    'name' => env('PWA_APP_NAME', config('app.name', 'Laravel') . ' Admin'),
    'short_name' => env('PWA_SHORT_NAME', 'Admin'),
    'description' => env('PWA_DESCRIPTION', 'Admin panel for ' . config('app.name', 'Laravel')),

    // Display Settings
    'start_url' => env('PWA_START_URL', '/admin'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'),
    'scope' => env('PWA_SCOPE', '/admin'),

    // Theme Configuration
    'theme_color' => env('PWA_THEME_COLOR', null), // Auto-detected from Filament
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),

    // Localization
    'lang' => env('PWA_LANG', null), // Auto-detected from Laravel
    'dir' => env('PWA_DIR', null),   // Auto-detected from language

    // Categories
    'categories' => [
        'productivity',
        'business',
        'utilities',
    ],

    // Installation Configuration
    'installation' => [
        'enabled' => env('PWA_INSTALLATION_ENABLED', true),
        'prompt_delay' => env('PWA_INSTALLATION_DELAY', 2000),
        'ios_instructions_delay' => env('PWA_IOS_INSTRUCTIONS_DELAY', 5000),
        'show_banner_in_debug' => env('PWA_SHOW_BANNER_IN_DEBUG', true),
    ],

    // Icon Configuration
    'icons' => [
        'path' => env('PWA_ICONS_PATH', 'images/icons'),
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
    ],

    // Service Worker Configuration
    'service_worker' => [
        'cache_name' => env('PWA_CACHE_NAME', 'filament-admin-v1.0.0'),
        'offline_url' => env('PWA_OFFLINE_URL', '/offline'),
        'cache_urls' => [
            '/admin',
            '/admin/login',
            '/manifest.json',
        ],
        'cache_patterns' => [
            'filament_assets' => '/\/css\/filament\/|\/js\/filament\//',
            'images' => '/\.(png|jpg|jpeg|svg|gif|webp|ico)$/',
            'fonts' => '/\.(woff|woff2|ttf|eot)$/',
        ],
    ],

    // App Shortcuts
    'shortcuts' => [
        [
            'name' => 'Dashboard',
            'short_name' => 'Dashboard',
            'description' => 'Go to the main dashboard',
            'url' => '/admin',
            'icons' => [
                [
                    'src' => '/images/icons/icon-96x96.png',
                    'sizes' => '96x96',
                ],
            ],
        ],
    ],

    // Advanced Options
    'prefer_related_applications' => env('PWA_PREFER_NATIVE_APP', false),
    'screenshots' => [],
    'related_applications' => [],
];
```

## Environment Variables

You can override any configuration option using environment variables in your `.env` file:

```env
# App Information
PWA_APP_NAME="My Custom Admin"
PWA_SHORT_NAME="MyAdmin"
PWA_DESCRIPTION="Custom admin panel"

# Display Settings
PWA_START_URL="/admin"
PWA_DISPLAY="standalone"
PWA_ORIENTATION="portrait-primary"
PWA_SCOPE="/admin"

# Theme
PWA_THEME_COLOR="#3B82F6"
PWA_BACKGROUND_COLOR="#ffffff"

# Localization
PWA_LANG="en"
PWA_DIR="ltr"

# Installation
PWA_INSTALLATION_ENABLED=true
PWA_INSTALLATION_DELAY=2000
PWA_IOS_INSTRUCTIONS_DELAY=5000
PWA_SHOW_BANNER_IN_DEBUG=true

# Icons
PWA_ICONS_PATH="images/icons"

# Service Worker
PWA_CACHE_NAME="my-app-v1.0.0"
PWA_OFFLINE_URL="/offline"

# Advanced
PWA_PREFER_NATIVE_APP=false
```

## Smart Defaults

The plugin automatically detects several configuration values:

### Theme Color Detection
- Attempts to detect from Filament panel colors
- Falls back to Tailwind Indigo 500 (`#6366f1`)

### Language Detection
- Uses Laravel's `app()->getLocale()`
- Falls back to `'en'`

### Text Direction Detection
- Automatically sets `'rtl'` for RTL languages (Arabic, Hebrew, Persian, etc.)
- Falls back to `'ltr'`

### App Name Detection
- Uses Laravel's `config('app.name')` with " Admin" suffix
- Falls back to `'Laravel Admin'`

## Validation

Use the validation command to check your configuration:

```bash
php artisan filament-pwa:setup --validate
```

This validates:
- Required configuration values
- File existence (manifest, service worker, icons)
- HTTPS requirements in production
- Icon size requirements

### Debug Information

Get detailed debug information about color detection and configuration:

```php
use Alareqi\FilamentPwa\Services\PwaService;

// In a controller or command
$debug = PwaService::debugColorDetection();
dd($debug);
```

## Configuration Examples

### Multi-tenant Configuration

```php
FilamentPwaPlugin::make()
    ->name(fn() => Filament::getTenant()?->name . ' Admin' ?? 'Admin Panel')
    ->themeColor(fn() => Filament::getTenant()?->primary_color ?? '#3B82F6')
    ->addShortcut(fn() => [
        'name' => 'Tenant Dashboard',
        'url' => '/admin/' . Filament::getTenant()?->slug,
        'description' => 'Go to tenant dashboard',
    ])
```

### Environment-specific Configuration

```php
FilamentPwaPlugin::make()
    ->name(fn() => match(app()->environment()) {
        'production' => 'Production Admin',
        'staging' => 'Staging Admin',
        default => 'Development Admin'
    })
    ->themeColor(fn() => match(app()->environment()) {
        'production' => '#dc2626',  // Red for production
        'staging' => '#f59e0b',     // Yellow for staging
        default => '#3B82F6'        // Blue for development
    })
    ->enableDebugBanner(app()->environment('local'))
```

### User-specific Configuration

```php
FilamentPwaPlugin::make()
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())
    ->direction(fn() => auth()->user()?->text_direction ?? 'ltr')
    ->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')
    ->addShortcut(fn() => [
        'name' => 'My Profile',
        'url' => '/admin/profile',
        'description' => 'View my profile',
        'icons' => [
            [
                'src' => auth()->user()?->avatar_url ?? '/images/default-avatar.png',
                'sizes' => '96x96',
            ],
        ],
    ])
```
