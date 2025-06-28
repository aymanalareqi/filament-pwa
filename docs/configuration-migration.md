# Configuration Migration Guide

This guide helps you migrate from the old Filament PWA configuration structure to the new simplified and enhanced configuration system.

## What's Changed

### 1. Simplified Configuration Structure

The new configuration is more organized and removes unused options:

**Old Structure:**
```php
return [
    'app_name' => 'My App',
    'installation_prompts' => [
        'enabled' => true,
        'delay' => 2000,
        'ios_instructions_delay' => 5000,
    ],
    'icons' => [
        'source_path' => 'icon.svg',        // ❌ Removed (unused)
        'output_path' => 'images/icons',
        'additional_sizes' => [16, 32],     // ❌ Removed (unused)
    ],
    'route_middleware' => ['web'],          // ❌ Simplified
];
```

**New Structure:**
```php
return [
    'name' => 'My App',                     // ✅ Renamed from 'app_name'
    'installation' => [                     // ✅ Renamed from 'installation_prompts'
        'enabled' => true,
        'prompt_delay' => 2000,             // ✅ Renamed from 'delay'
        'ios_instructions_delay' => 5000,
    ],
    'icons' => [
        'path' => 'images/icons',           // ✅ Renamed from 'output_path'
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
    ],
];
```

### 2. Enhanced Fluent API

The plugin now provides a comprehensive fluent API for programmatic configuration:

```php
// New fluent API approach
FilamentPwaPlugin::make()
    ->name('My App')
    ->themeColor('#3B82F6')
    ->standalone()
    ->language('en')
    ->ltr()
    ->enableInstallation()
```

## Migration Steps

### Step 1: Backup Your Current Configuration

Before migrating, backup your current `config/filament-pwa.php` file:

```bash
cp config/filament-pwa.php config/filament-pwa.php.backup
```

### Step 2: Update Configuration Keys

Update the following keys in your configuration file:

| Old Key | New Key | Notes |
|---------|---------|-------|
| `app_name` | `name` | Backward compatible |
| `installation_prompts` | `installation` | Backward compatible |
| `installation_prompts.delay` | `installation.prompt_delay` | Backward compatible |
| `icons.output_path` | `icons.path` | Backward compatible |
| `icons.source_path` | ❌ Removed | Was unused |
| `icons.additional_sizes` | ❌ Removed | Was unused |
| `route_middleware` | ❌ Simplified | Now handled internally |

### Step 3: Remove Unused Configuration

Remove these unused configuration options:

```php
// ❌ Remove these (they were never used)
'icons' => [
    'source_path' => 'icon.svg',        // Remove
    'additional_sizes' => [16, 32],     // Remove
],
'route_middleware' => ['web'],          // Remove
'screenshots' => [],                    // Remove if empty
'related_applications' => [],           // Remove if empty
```

### Step 4: Choose Your Configuration Approach

#### Option A: Keep Using Configuration File

Update your `config/filament-pwa.php` to use the new structure:

```php
<?php

return [
    // Basic app information
    'name' => env('PWA_APP_NAME', config('app.name', 'Laravel') . ' Admin'),
    'short_name' => env('PWA_SHORT_NAME', 'Admin'),
    'description' => env('PWA_DESCRIPTION', 'Admin panel for ' . config('app.name', 'Laravel')),

    // Display settings
    'start_url' => env('PWA_START_URL', '/admin'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'),
    'scope' => env('PWA_SCOPE', '/admin'),

    // Theme
    'theme_color' => env('PWA_THEME_COLOR', '#A77B56'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#ffffff'),

    // Localization
    'lang' => env('PWA_LANG', 'en'),
    'dir' => env('PWA_DIR', 'ltr'),

    // Categories
    'categories' => [
        'productivity',
        'business',
        'utilities',
    ],

    // Installation
    'installation' => [
        'enabled' => env('PWA_INSTALLATION_ENABLED', true),
        'prompt_delay' => env('PWA_INSTALLATION_DELAY', 2000),
        'ios_instructions_delay' => env('PWA_IOS_INSTRUCTIONS_DELAY', 5000),
    ],

    // Icons
    'icons' => [
        'path' => env('PWA_ICONS_PATH', 'images/icons'),
        'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
        'maskable_sizes' => [192, 512],
    ],

    // Service worker
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

    // Shortcuts
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

    // Advanced options
    'prefer_related_applications' => env('PWA_PREFER_NATIVE_APP', false),
    'screenshots' => [],
    'related_applications' => [],
];
```

#### Option B: Switch to Fluent API

Remove most configuration from the file and use the fluent API in your panel provider:

```php
// Minimal config/filament-pwa.php
<?php

return [
    // Keep only environment-specific or complex configurations here
    'service_worker' => [
        'cache_patterns' => [
            'filament_assets' => '/\/css\/filament\/|\/js\/filament\//',
            'images' => '/\.(png|jpg|jpeg|svg|gif|webp|ico)$/',
            'fonts' => '/\.(woff|woff2|ttf|eot)$/',
        ],
    ],
];
```

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
                ->description('Powerful admin panel')
                ->themeColor('#3B82F6')
                ->backgroundColor('#ffffff')
                ->standalone()
                ->portrait()
                ->english()
                ->enableInstallation(2000)
                ->addShortcut('Dashboard', '/admin')
                ->addShortcut('Users', '/admin/users')
                ->serviceWorker('my-app-v1.0.0'),
        ]);
}
```

## Backward Compatibility

The new system maintains full backward compatibility:

- ✅ Old configuration keys still work
- ✅ Existing installations won't break
- ✅ You can migrate gradually
- ✅ Mix old config file with new fluent API

## Testing Your Migration

After migrating, test your configuration:

1. **Validate configuration:**
   ```bash
   php artisan filament-pwa:setup --validate
   ```

2. **Check manifest generation:**
   ```bash
   curl http://your-app.test/manifest.json
   ```

3. **Test installation prompts:**
   - Open your admin panel in a browser
   - Check if installation prompts appear correctly

4. **Verify RTL/LTR support:**
   - Test with different `dir` settings
   - Ensure UI elements position correctly

## Common Migration Issues

### Issue 1: Installation Prompts Not Working

**Problem:** Installation prompts stopped working after migration.

**Solution:** Check the new installation configuration structure:

```php
// Old (still works)
'installation_prompts' => [
    'enabled' => true,
    'delay' => 2000,
]

// New (recommended)
'installation' => [
    'enabled' => true,
    'prompt_delay' => 2000,
]
```

### Issue 2: Icons Not Loading

**Problem:** Icons are not loading after migration.

**Solution:** Update the icon path configuration:

```php
// Old (still works)
'icons' => [
    'output_path' => 'images/icons',
]

// New (recommended)
'icons' => [
    'path' => 'images/icons',
]
```

### Issue 3: Service Worker Cache Issues

**Problem:** Service worker not updating after migration.

**Solution:** Update the cache name to force cache refresh:

```php
'service_worker' => [
    'cache_name' => 'filament-admin-v2.0.0', // Increment version
]
```

## Getting Help

If you encounter issues during migration:

1. Check the [Configuration Guide](configuration-guide.md)
2. Run the validation command: `php artisan filament-pwa:setup --validate`
3. Check the browser console for errors
4. Review the [Troubleshooting Guide](troubleshooting.md)

## Benefits of the New System

- 🚀 **Better Developer Experience:** Fluent API with IDE autocompletion
- 🧹 **Cleaner Configuration:** Removed unused options
- 🌍 **Enhanced RTL/LTR Support:** Better internationalization
- 🔧 **More Flexible:** Mix config file and fluent API approaches
- 📚 **Better Documentation:** Comprehensive guides and examples
- ⚡ **Improved Performance:** Optimized configuration loading
