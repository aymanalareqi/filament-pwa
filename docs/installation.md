# Installation Guide

This guide will walk you through installing and setting up the Filament PWA plugin.

## Requirements

- PHP 8.1 or higher
- Laravel 10.0 or higher
- Filament 3.0 or higher

## Installation

### 1. Install via Composer

```bash
composer require alareqi/filament-pwa
```

### 2. Publish Configuration

Publish the configuration file to customize PWA settings:

```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

This will create a `config/filament-pwa.php` file where you can customize all PWA settings.

### 3. Register the Plugin

Add the plugin to your Filament Panel provider:

```php
<?php

namespace App\Providers\Filament;

use Alareqi\FilamentPwa\FilamentPwaPlugin;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ... other configuration
            ->plugin(FilamentPwaPlugin::make());
    }
}
```

### 4. Publish Assets (Optional)

If you want to customize the PWA views or assets:

```bash
# Publish views for customization
php artisan vendor:publish --tag="filament-pwa-views"

# Publish assets
php artisan vendor:publish --tag="filament-pwa-assets"
```

### 5. Generate PWA Icons

Generate all required PWA icons from your logo:

```bash
# Using SVG (recommended for best quality)
php artisan filament-pwa:setup --generate-icons --source=public/logo.svg

# Using PNG
php artisan filament-pwa:setup --generate-icons --source=public/logo.png
```

### 6. Validate Setup

Verify your PWA setup is correct:

```bash
php artisan filament-pwa:setup --validate
```

## Quick Setup Command

You can also use the all-in-one setup command:

```bash
php artisan filament-pwa:setup --publish-assets --generate-icons --source=public/logo.svg --validate
```

## Environment Configuration

Add these environment variables to your `.env` file for easy configuration:

```env
# PWA Configuration
PWA_APP_NAME="${APP_NAME} Admin"
PWA_SHORT_NAME="Admin"
PWA_DESCRIPTION="Admin panel for ${APP_NAME}"
PWA_START_URL="/admin"
PWA_THEME_COLOR="#A77B56"
PWA_BACKGROUND_COLOR="#ffffff"
PWA_LANG="en"
PWA_DIR="ltr"

# Installation Prompts
PWA_INSTALLATION_PROMPTS=true
PWA_INSTALLATION_DELAY=2000
PWA_IOS_INSTRUCTIONS_DELAY=5000

# Service Worker
PWA_CACHE_NAME="filament-admin-v1.0.0"
PWA_OFFLINE_URL="/offline"

# Icons
PWA_ICON_SOURCE="icon.svg"
```

## HTTPS Requirement

PWAs require HTTPS in production. Make sure your application is served over HTTPS when deployed.

For local development, you can use tools like:
- Laravel Valet (automatically provides HTTPS)
- Laravel Sail with SSL
- ngrok for tunneling

## Next Steps

- [Configuration Guide](configuration.md) - Customize your PWA settings
- [Icon Generation](icon-generation.md) - Learn about icon requirements and generation
- [Customization](customization.md) - Customize views and behavior
- [Troubleshooting](troubleshooting.md) - Common issues and solutions

## Verification

After installation, you should see:

1. PWA meta tags in your admin panel's HTML head
2. Installation prompts when accessing the admin panel
3. The ability to install the admin panel as a PWA
4. Offline functionality when the network is unavailable

Visit your admin panel and check the browser's developer tools to verify:
- Service worker is registered
- Manifest is loaded correctly
- PWA installation criteria are met
