# Filament PWA Configuration Guide

This guide covers the two main approaches to configuring your Filament PWA: using the configuration file and using the fluent plugin API.

## Configuration Approaches

### 1. Configuration File (Traditional)

The traditional approach uses the `config/filament-pwa.php` configuration file. This is ideal for static configurations that don't change based on environment or user preferences.

```php
// config/filament-pwa.php
return [
    'name' => 'My Admin Panel',
    'short_name' => 'Admin',
    'description' => 'Powerful admin panel for my application',
    'start_url' => '/admin',
    'display' => 'standalone',
    'theme_color' => '#3B82F6',
    'background_color' => '#ffffff',
    'lang' => 'en',
    'dir' => 'ltr',
    
    'installation' => [
        'enabled' => true,
        'prompt_delay' => 2000,
        'ios_instructions_delay' => 5000,
    ],
    
    'icons' => [
        'path' => 'images/icons',
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
    ],
    
    'service_worker' => [
        'cache_name' => 'my-admin-v1.0.0',
        'offline_url' => '/offline',
        'cache_urls' => ['/admin', '/admin/login'],
    ],
];
```

### 2. Fluent Plugin API (Recommended)

The fluent API approach allows you to configure the PWA programmatically in your panel provider. This is more flexible and allows for dynamic configuration.

```php
// app/Providers/Filament/AdminPanelProvider.php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPwaPlugin::make()
                ->name('My Admin Panel')
                ->shortName('Admin')
                ->description('Powerful admin panel for my application')
                ->startUrl('/admin')
                ->themeColor('#3B82F6')
                ->backgroundColor('#ffffff')
                ->standalone()
                ->portrait()
                ->english()
                ->enableInstallation(2000)
                ->icons('images/icons')
                ->serviceWorker('my-admin-v1.0.0', '/offline')
                ->addShortcut('Dashboard', '/admin', 'Go to dashboard')
                ->addShortcut('Users', '/admin/users', 'Manage users'),
        ]);
}
```

## Configuration Options

### Basic App Information

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `name` | string | `config('app.name') . ' Admin'` | Full application name |
| `short_name` | string | `'Admin'` | Short name for home screen |
| `description` | string | `'Admin panel for ' . config('app.name')` | App description |
| `start_url` | string | `'/admin'` | URL to open when app is launched |

### Display & Appearance

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `display` | string | `'standalone'` | Display mode: `standalone`, `fullscreen`, `minimal-ui`, `browser` |
| `orientation` | string | `'portrait-primary'` | Screen orientation: `portrait`, `landscape`, `any` |
| `theme_color` | string | Auto-detected | Theme color for browser UI (auto-detects from Filament's primary color) |
| `background_color` | string | `'#ffffff'` | Background color for splash screen |
| `scope` | string | `'/admin'` | Navigation scope for the PWA |

### Localization

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `lang` | string | Auto-detected | Language code (auto-detects from Laravel's `app()->getLocale()`) |
| `dir` | string | Auto-detected | Text direction (auto-detects based on language: RTL for Arabic, Hebrew, etc.) |

### Installation Settings

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `installation.enabled` | boolean | `true` | Enable installation prompts |
| `installation.prompt_delay` | integer | `2000` | Delay before showing prompt (ms) |
| `installation.ios_instructions_delay` | integer | `5000` | Delay for iOS instructions (ms) |
| `installation.show_banner_in_debug` | boolean | `true` | Always show banner in debug mode (development only) |

## Auto-Detection Features

The PWA plugin automatically inherits sensible defaults from your Filament and Laravel configuration:

### Theme Color Auto-Detection

The `theme_color` automatically detects and uses Filament's primary color:

```php
// If you have configured Filament colors like this:
$panel->colors([
    'primary' => Color::Indigo,  // or '#6366f1'
])

// The PWA will automatically use the same color
// No need to manually set theme_color in PWA config
```

**Fallback Logic:**
1. Tries to get Filament panel's primary color (600 shade preferred, 500 as fallback)
2. Converts RGB color arrays to hex format
3. Falls back to `#6366f1` (Tailwind Indigo 500) if Filament is not available

### Language Auto-Detection

The `lang` setting automatically uses Laravel's current locale:

```php
// If your Laravel app is configured with:
'locale' => 'ar',  // in config/app.php

// The PWA will automatically use 'ar' as the language
// No need to manually set lang in PWA config
```

### Text Direction Auto-Detection

The `dir` setting automatically detects text direction based on the language:

**RTL Languages (automatically detected):**
- Arabic (`ar`)
- Hebrew (`he`)
- Persian/Farsi (`fa`)
- Urdu (`ur`)
- Kurdish (`ku`)
- Dhivehi (`dv`)
- Pashto (`ps`)
- Sindhi (`sd`)
- Yiddish (`yi`)

**All other languages default to LTR.**

### Manual Override

You can still manually override any auto-detected values:

```php
// config/filament-pwa.php
return [
    'theme_color' => '#ff0000',  // Override auto-detection
    'lang' => 'fr',              // Override auto-detection
    'dir' => 'rtl',              // Override auto-detection
];
```

```php
// Or via environment variables
PWA_THEME_COLOR=#ff0000
PWA_LANG=fr
PWA_DIR=rtl
```

## Fluent API Methods

### Basic Configuration

```php
FilamentPwaPlugin::make()
    ->name('My App')                    // Set app name
    ->appName('My App')                 // Alias for name()
    ->shortName('App')                  // Set short name
    ->description('My awesome app')     // Set description
    ->startUrl('/admin')                // Set start URL
    ->scope('/admin')                   // Set navigation scope
```

### Theme & Appearance

```php
FilamentPwaPlugin::make()
    ->themeColor('#3B82F6')            // Set theme color
    ->backgroundColor('#ffffff')        // Set background color
    ->displayMode('standalone')         // Set display mode
    ->standalone()                      // Shortcut for standalone mode
    ->fullscreen()                      // Shortcut for fullscreen mode
    ->orientation('portrait')           // Set orientation
    ->portrait()                        // Shortcut for portrait
    ->landscape()                       // Shortcut for landscape
```

### Localization

```php
FilamentPwaPlugin::make()
    ->language('en')                    // Set language
    ->direction('ltr')                  // Set text direction
    ->ltr()                            // Shortcut for LTR
    ->rtl()                            // Shortcut for RTL

    // Dynamic language based on user preference
    ->language(fn() => auth()->user()?->language ?? 'en')
    ->direction(fn() => auth()->user()?->isRtlLanguage() ? 'rtl' : 'ltr')
```

### Installation & Features

```php
FilamentPwaPlugin::make()
    ->installation(true, 2000, 5000)   // Configure installation prompts
    ->enableInstallation(2000)         // Enable with custom delay
    ->disableInstallation()            // Disable installation prompts
    ->enableDebugBanner()              // Always show banner in debug mode
    ->disableDebugBanner()             // Disable debug banner mode
    ->icons('images/icons')            // Configure icon path
    ->serviceWorker('cache-v1', '/offline') // Configure service worker
```

### Shortcuts

```php
FilamentPwaPlugin::make()
    ->shortcuts([...])                 // Set all shortcuts at once
    ->addShortcut('Dashboard', '/admin', 'Go to dashboard')
    ->addShortcut('Users', '/admin/users', 'Manage users')
    ->addShortcut('Settings', '/admin/settings')

    // Dynamic shortcut based on user permissions
    ->addShortcut(fn() => [
        'name' => 'Admin Panel',
        'short_name' => 'Admin',
        'description' => 'Access admin features',
        'url' => auth()->user()?->can('access-admin') ? '/admin' : '/dashboard',
        'icons' => [['src' => '/images/icons/icon-96x96.png', 'sizes' => '96x96']],
    ])
```

## Dynamic Configuration with Closures

The plugin supports closures for dynamic configuration that is evaluated at runtime. This allows you to create configurations that adapt based on user preferences, environment variables, database settings, or any other runtime conditions.

### Basic Dynamic Configuration

```php
FilamentPwaPlugin::make()
    // Dynamic app name based on environment
    ->name(fn() => config('app.env') === 'production' ? 'Admin Panel' : 'Admin Panel (Dev)')

    // Dynamic theme color based on user preference
    ->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')

    // Dynamic description with user context
    ->description(fn() => 'Admin panel for ' . (auth()->user()?->company_name ?? config('app.name')))

    // Dynamic start URL based on user role
    ->startUrl(fn() => auth()->user()?->isAdmin() ? '/admin' : '/dashboard')
```

### Advanced Dynamic Shortcuts

```php
FilamentPwaPlugin::make()
    // Add shortcuts based on user permissions
    ->addShortcut(fn() => [
        'name' => 'Users',
        'short_name' => 'Users',
        'description' => 'Manage users',
        'url' => '/admin/users',
        'icons' => [['src' => '/images/icons/users.png', 'sizes' => '96x96']],
    ])

    // Conditional shortcut
    ->addShortcut(fn() => auth()->user()?->can('manage-reports') ? [
        'name' => 'Reports',
        'short_name' => 'Reports',
        'description' => 'View reports',
        'url' => '/admin/reports',
        'icons' => [['src' => '/images/icons/reports.png', 'sizes' => '96x96']],
    ] : null) // Return null to skip this shortcut
```

### Dynamic Localization

```php
FilamentPwaPlugin::make()
    // Language based on user preference or browser
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())

    // Direction based on language
    ->direction(fn() => in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr')

    // Theme color based on locale
    ->themeColor(fn() => match(app()->getLocale()) {
        'ar' => '#10B981', // Green for Arabic
        'en' => '#3B82F6', // Blue for English
        'es' => '#F59E0B', // Yellow for Spanish
        default => '#6B7280', // Gray for others
    })
```

### Environment-Based Configuration

```php
FilamentPwaPlugin::make()
    // Different configurations per environment
    ->name(fn() => match(config('app.env')) {
        'production' => 'Admin Panel',
        'staging' => 'Admin Panel (Staging)',
        'local' => 'Admin Panel (Local)',
        default => 'Admin Panel (Dev)',
    })

    // Debug information in development
    ->description(fn() => config('app.debug')
        ? 'Admin panel with debug mode enabled'
        : 'Professional admin panel'
    )
```

## Advanced Configuration

### Custom Icon Configuration

```php
FilamentPwaPlugin::make()
    ->icons(
        path: 'custom/icons',
        sizes: [96, 192, 512],
        maskableSizes: [192, 512]
    )
```

### Service Worker Configuration

```php
FilamentPwaPlugin::make()
    ->serviceWorker(
        cacheName: 'my-app-v2.0.0',
        offlineUrl: '/custom-offline',
        cacheUrls: ['/admin', '/admin/dashboard', '/admin/users']
    )
```

### Categories

```php
FilamentPwaPlugin::make()
    ->categories(['productivity', 'business', 'utilities'])
```

## Debug Mode Configuration

The plugin includes a special debug mode feature for development environments:

### Debug Installation Banner

When `config('app.debug')` is `true` and `installation.show_banner_in_debug` is enabled, the PWA installation banner will always be displayed, bypassing:

- User dismissal actions (localStorage checks)
- Browser installation state detection
- Normal installation prompt requirements

This is extremely useful for testing the PWA installation flow during development.

```php
// config/filament-pwa.php
return [
    'installation' => [
        'enabled' => true,
        'show_banner_in_debug' => true, // Always show in debug mode
    ],
];
```

```php
// Using fluent API
FilamentPwaPlugin::make()
    ->enableDebugBanner()  // Enable debug banner mode
    ->disableDebugBanner() // Disable debug banner mode
```

**Important Notes:**
- This feature only works when `APP_DEBUG=true` in your `.env` file
- It has no effect in production environments (`APP_DEBUG=false`)
- The banner will show immediately on page load in debug mode
- Users can still interact with the banner normally (install/dismiss)

### Environment Variables

You can control the debug banner via environment variables:

```env
# .env file
APP_DEBUG=true
PWA_SHOW_BANNER_IN_DEBUG=true
```

## Environment-Based Configuration

You can use environment variables in your configuration file:

```php
// config/filament-pwa.php
return [
    'name' => env('PWA_APP_NAME', config('app.name') . ' Admin'),
    'theme_color' => env('PWA_THEME_COLOR', null), // Auto-detects from Filament
    'lang' => env('PWA_LANG', null), // Auto-detects from Laravel
    'dir' => env('PWA_DIR', null), // Auto-detects from language
    'installation' => [
        'enabled' => env('PWA_INSTALLATION_ENABLED', true),
        'show_banner_in_debug' => env('PWA_SHOW_BANNER_IN_DEBUG', true),
    ],
];
```

Then in your `.env` file:

```env
PWA_APP_NAME="My Production App"
# PWA_THEME_COLOR="#FF6B6B"  # Optional: Override auto-detection
# PWA_LANG="fr"              # Optional: Override auto-detection
# PWA_DIR="rtl"              # Optional: Override auto-detection
PWA_INSTALLATION_ENABLED=true
PWA_SHOW_BANNER_IN_DEBUG=true
```

## Best Practices

1. **Use the fluent API for dynamic configurations** that might change based on user preferences or environment.

2. **Use the config file for static settings** that are the same across all environments.

3. **Combine both approaches** - use the config file for defaults and override specific values with the fluent API.

4. **Test your configuration** using the validation command:
   ```bash
   php artisan filament-pwa:setup --validate
   ```

5. **Keep your cache name updated** when making significant changes to ensure proper cache invalidation.

## Migration from Old Configuration

If you're upgrading from an older version, the plugin maintains backward compatibility:

- `app_name` is now `name` (but `app_name` still works)
- `installation_prompts` is now `installation` (but old structure still works)
- `icons.output_path` is now `icons.path` (but old key still works)

The plugin will automatically handle both old and new configuration keys.
