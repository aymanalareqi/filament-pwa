# Configuration Guide

This guide covers all available configuration options for the Filament PWA plugin.

## Basic Configuration

### Plugin Registration

You can configure the plugin directly when registering it:

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            FilamentPwaPlugin::make()
                ->appName('My Admin Panel')
                ->shortName('Admin')
                ->description('Powerful admin panel for my application')
                ->themeColor('#3B82F6')
                ->backgroundColor('#ffffff')
                ->startUrl('/admin')
                ->displayMode('standalone')
                ->orientation('portrait-primary')
                ->scope('/admin')
                ->language('en')
                ->direction('ltr')
                ->installationPrompts(true)
        );
}
```

### Configuration File

Alternatively, configure everything in `config/filament-pwa.php`:

```php
return [
    // App Information
    'app_name' => env('PWA_APP_NAME', config('app.name', 'Laravel') . ' Admin'),
    'short_name' => env('PWA_SHORT_NAME', 'Admin'),
    'description' => env('PWA_DESCRIPTION', 'Admin panel for ' . config('app.name', 'Laravel')),

    // Display Settings
    'start_url' => env('PWA_START_URL', '/admin'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'),
    'scope' => env('PWA_SCOPE', '/admin'),

    // Theme Configuration
    'theme_color' => env('PWA_THEME_COLOR', '#A77B56'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),

    // Localization
    'lang' => env('PWA_LANG', 'en'),
    'dir' => env('PWA_DIR', 'ltr'),

    // ... more options
];
```

## Configuration Options

### App Information

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `app_name` | string | `{APP_NAME} Admin` | Full name of your PWA |
| `short_name` | string | `Admin` | Short name (12 chars max recommended) |
| `description` | string | `Admin panel for {APP_NAME}` | Description of your PWA |

### Display Settings

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `start_url` | string | `/admin` | URL to open when PWA is launched |
| `display` | string | `standalone` | Display mode: `standalone`, `fullscreen`, `minimal-ui`, `browser` |
| `orientation` | string | `portrait-primary` | Preferred orientation |
| `scope` | string | `/admin` | Navigation scope for the PWA |

### Theme Configuration

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `theme_color` | string | `#A77B56` | Theme color for browser UI |
| `background_color` | string | `#ffffff` | Background color for splash screen |

### Localization

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `lang` | string | `en` | Language code |
| `dir` | string | `ltr` | Text direction: `ltr` or `rtl` |

### Installation Prompts

```php
'installation_prompts' => [
    'enabled' => true,
    'delay' => 2000, // milliseconds
    'ios_instructions_delay' => 5000, // milliseconds
],
```

### Icon Configuration

```php
'icons' => [
    'source_path' => 'icon.svg',
    'output_path' => 'images/icons',
    'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
    'maskable_sizes' => [192, 512],
    'additional_sizes' => [16, 32, 70, 150, 310],
],
```

### Service Worker Configuration

```php
'service_worker' => [
    'cache_name' => 'filament-admin-v1.0.0',
    'offline_url' => '/offline',
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
```

### Shortcuts

Define app shortcuts that appear in the PWA menu:

```php
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
    [
        'name' => 'Users',
        'short_name' => 'Users',
        'description' => 'Manage users',
        'url' => '/admin/users',
        'icons' => [
            [
                'src' => '/images/icons/icon-96x96.png',
                'sizes' => '96x96',
            ],
        ],
    ],
],
```

### Screenshots

Add screenshots for enhanced installation prompts:

```php
'screenshots' => [
    [
        'src' => '/images/screenshots/desktop.png',
        'sizes' => '1280x720',
        'type' => 'image/png',
        'form_factor' => 'wide',
    ],
    [
        'src' => '/images/screenshots/mobile.png',
        'sizes' => '375x667',
        'type' => 'image/png',
        'form_factor' => 'narrow',
    ],
],
```

## Environment Variables

All configuration options can be overridden using environment variables:

```env
# App Information
PWA_APP_NAME="My Custom Admin"
PWA_SHORT_NAME="MyAdmin"
PWA_DESCRIPTION="Custom admin panel description"

# Display
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
PWA_INSTALLATION_PROMPTS=true
PWA_INSTALLATION_DELAY=2000
PWA_IOS_INSTRUCTIONS_DELAY=5000

# Service Worker
PWA_CACHE_NAME="my-admin-v1.0.0"
PWA_OFFLINE_URL="/offline"

# Icons
PWA_ICON_SOURCE="logo.svg"
```

## Advanced Configuration

### Custom Categories

```php
'categories' => [
    'productivity',
    'business',
    'utilities',
    'education',
],
```

### Related Applications

```php
'prefer_related_applications' => false,
'related_applications' => [
    [
        'platform' => 'play',
        'url' => 'https://play.google.com/store/apps/details?id=com.example.app',
        'id' => 'com.example.app',
    ],
],
```

### Route Middleware

Customize middleware for PWA routes:

```php
'route_middleware' => ['web', 'auth'],
```

## RTL Support

For right-to-left languages:

```php
'lang' => 'ar',
'dir' => 'rtl',
```

The plugin automatically adjusts layouts and styles for RTL languages.

## Next Steps

- [Icon Generation](icon-generation.md) - Learn about icon requirements
- [Customization](customization.md) - Customize views and behavior
- [Troubleshooting](troubleshooting.md) - Common issues and solutions
