# Filament PWA Plugin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alareqi/filament-pwa.svg?style=flat-square)](https://packagist.org/packages/alareqi/filament-pwa)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alareqi/filament-pwa/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/aymanalareqi/filament-pwa/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alareqi/filament-pwa/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/aymanalareqi/filament-pwa/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alareqi/filament-pwa.svg?style=flat-square)](https://packagist.org/packages/alareqi/filament-pwa)

A comprehensive Progressive Web App (PWA) plugin for Filament v3 admin panels. Transform your Filament admin panel into a fully-featured PWA with offline functionality, installation prompts, automatic icon generation, and more.

## Features

- 🚀 **Complete PWA Implementation** - Full PWA compliance with manifest, service worker, and offline functionality
- 📱 **Installation Prompts** - Smart installation banners with iOS-specific instructions
- 🎨 **Automatic Icon Generation** - Generate all required PWA icons from a single source image (SVG or PNG)
- 🌐 **Offline Support** - Comprehensive caching strategies and offline fallback pages
- 🔧 **Highly Configurable** - Extensive configuration options for all PWA settings
- 🎯 **Filament Integration** - Seamless integration with Filament panels using render hooks
- 📊 **Validation Tools** - Built-in PWA validation and testing commands
- 🌍 **RTL Support** - Full right-to-left language support
- 🎨 **Theme Integration** - Automatic theme color integration with Filament

## Installation

You can install the package via composer:

```bash
composer require alareqi/filament-pwa
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-pwa-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-pwa-config"
```

## Quick Start

### Method 1: Fluent API (Recommended)

Register the plugin in your Panel provider with fluent configuration:

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPwaPlugin::make()
                ->name('My Admin Panel')
                ->shortName('Admin')
                ->themeColor('#3B82F6')
                ->backgroundColor('#ffffff')
                ->standalone()
                ->language('en')
                ->ltr()
                ->enableInstallation()
                ->addShortcut('Dashboard', '/admin')
                ->addShortcut('Users', '/admin/users'),
        ]);
}
```

### Method 2: Configuration File

Alternatively, register the plugin and configure via config file:

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPwaPlugin::make(),
        ]);
}
```

Then configure in `config/filament-pwa.php`:

```php
return [
    'name' => 'My Admin Panel',
    'short_name' => 'Admin',
    'theme_color' => '#3B82F6',
    'installation' => [
        'enabled' => true,
        'prompt_delay' => 2000,
    ],
    // ... more options
];
```

### Setup Commands

1. **Publish PWA assets:**
   ```bash
   php artisan vendor:publish --tag="filament-pwa-assets"
   ```

2. **Generate PWA icons from your logo:**
   ```bash
   php artisan filament-pwa:setup --generate-icons --source=public/logo.svg
   ```

3. **Validate your PWA setup:**
   ```bash
   php artisan filament-pwa:setup --validate
   ```

That's it! Your Filament admin panel is now a fully-featured PWA with RTL/LTR support.

## Configuration

The plugin offers two configuration approaches:

### 1. Fluent API (Recommended)
```php
FilamentPwaPlugin::make()
    ->name('My App')
    ->themeColor('#3B82F6')
    ->standalone()
    ->rtl()  // For RTL languages
    ->enableInstallation()

    // Dynamic configuration with closures
    ->name(fn() => auth()->user()?->company_name ?? 'My App')
    ->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')
    ->language(fn() => auth()->user()?->language ?? 'en')
```

### 2. Configuration File
```php
// config/filament-pwa.php
return [
    'name' => 'My App',
    'theme_color' => '#3B82F6',
    'dir' => 'rtl',  // For RTL languages
    'installation' => ['enabled' => true],
];
```

## Documentation

- [Configuration Guide](docs/configuration-guide.md) - Complete configuration reference
- [Configuration Migration](docs/configuration-migration.md) - Upgrade from old versions
- [Installation Guide](docs/installation.md) - Detailed installation instructions
- [Icon Generation](docs/icon-generation.md) - PWA icon generation guide
- [Customization](docs/customization.md) - Advanced customization options
- [Troubleshooting](docs/troubleshooting.md) - Common issues and solutions

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ayman Alareqi](https://github.com/aymanalareqi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
