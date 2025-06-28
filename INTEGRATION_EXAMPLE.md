# Integration Example

This document shows how to integrate the Filament PWA plugin into your existing Laravel/Filament project, specifically replacing the current PWA implementation in the Alfarih Backend project.

## Current Implementation Analysis

The current project has:
- `app/Console/Commands/PWASetupCommand.php`
- `app/Services/PWAService.php`
- PWA views in `resources/views/pwa/`
- PWA integration in `AdminPanelProvider.php`

## Migration Steps

### 1. Install the Plugin

```bash
# Add to your main project's composer.json
composer require alareqi/filament-pwa
```

### 2. Remove Old Implementation

Remove these files from your main project:
```bash
rm app/Console/Commands/PWASetupCommand.php
rm app/Services/PWAService.php
rm -rf resources/views/pwa/
```

### 3. Update AdminPanelProvider

Replace the current PWA implementation in `app/Providers/Filament/AdminPanelProvider.php`:

```php
<?php

namespace App\Providers\Filament;

use Alareqi\FilamentPwa\FilamentPwaPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#A77B56'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Replace old PWA implementation with plugin
            ->plugin(
                FilamentPwaPlugin::make()
                    ->appName(config('app.name') . ' Admin')
                    ->shortName('الفارح Admin')
                    ->description('نظام إدارة محتوى الفارح')
                    ->themeColor('#A77B56')
                    ->backgroundColor('#ffffff')
                    ->startUrl('/admin')
                    ->displayMode('standalone')
                    ->orientation('portrait-primary')
                    ->scope('/admin')
                    ->language('ar')
                    ->direction('rtl')
                    ->categories(['productivity', 'business', 'education'])
                    ->shortcuts([
                        [
                            'name' => 'لوحة التحكم',
                            'short_name' => 'الرئيسية',
                            'description' => 'الانتقال إلى لوحة التحكم الرئيسية',
                            'url' => '/admin',
                            'icons' => [
                                [
                                    'src' => '/images/icons/icon-96x96.png',
                                    'sizes' => '96x96',
                                ],
                            ],
                        ],
                        [
                            'name' => 'المحتوى',
                            'short_name' => 'المحتوى',
                            'description' => 'إدارة المحتوى',
                            'url' => '/admin/contents',
                            'icons' => [
                                [
                                    'src' => '/images/icons/icon-96x96.png',
                                    'sizes' => '96x96',
                                ],
                            ],
                        ],
                        [
                            'name' => 'الفئات',
                            'short_name' => 'الفئات',
                            'description' => 'إدارة الفئات',
                            'url' => '/admin/categories',
                            'icons' => [
                                [
                                    'src' => '/images/icons/icon-96x96.png',
                                    'sizes' => '96x96',
                                ],
                            ],
                        ],
                    ])
                    ->installationPrompts(true)
            );
    }
}
```

### 4. Update Environment Configuration

Add these to your `.env` file:

```env
# PWA Configuration
PWA_APP_NAME="الفارح Admin"
PWA_SHORT_NAME="الفارح"
PWA_DESCRIPTION="نظام إدارة محتوى الفارح"
PWA_START_URL="/admin"
PWA_THEME_COLOR="#A77B56"
PWA_BACKGROUND_COLOR="#ffffff"
PWA_LANG="ar"
PWA_DIR="rtl"

# Installation Prompts
PWA_INSTALLATION_PROMPTS=true
PWA_INSTALLATION_DELAY=2000
PWA_IOS_INSTRUCTIONS_DELAY=5000

# Service Worker
PWA_CACHE_NAME="alfarih-admin-v1.0.0"
PWA_OFFLINE_URL="/offline"

# Icons
PWA_ICON_SOURCE="icon.svg"
```

### 5. Publish and Configure

```bash
# Publish configuration
php artisan vendor:publish --tag="filament-pwa-config"

# Publish views for customization (optional)
php artisan vendor:publish --tag="filament-pwa-views"

# Generate icons from your existing logo
php artisan filament-pwa:setup --generate-icons --source=public/icon.svg

# Validate setup
php artisan filament-pwa:setup --validate
```

### 6. Customize for Arabic/RTL

If you published the views, customize them for Arabic:

```php
// config/filament-pwa.php
return [
    'lang' => 'ar',
    'dir' => 'rtl',
    
    // Add Arabic shortcuts
    'shortcuts' => [
        [
            'name' => 'لوحة التحكم',
            'short_name' => 'الرئيسية',
            'description' => 'الانتقال إلى لوحة التحكم الرئيسية',
            'url' => '/admin',
            'icons' => [
                [
                    'src' => '/images/icons/icon-96x96.png',
                    'sizes' => '96x96',
                ],
            ],
        ],
        // ... more shortcuts
    ],
];
```

### 7. Create Arabic Translations

Create `resources/lang/ar/filament-pwa.php`:

```php
<?php

return [
    'install_title' => 'تثبيت التطبيق',
    'install_description' => 'احصل على تجربة أفضل مع التطبيق المثبت',
    'install_button' => 'تثبيت',
    'dismiss_button' => 'إلغاء',
    
    'ios_install_title' => 'تثبيت التطبيق على iOS',
    'ios_install_description' => 'لتثبيت هذا التطبيق على iOS:',
    'ios_step_1' => 'اضغط على زر المشاركة',
    'ios_step_2' => 'اختر "إضافة إلى الشاشة الرئيسية"',
    'ios_step_3' => 'اضغط "إضافة" للتأكيد',
    'got_it' => 'فهمت',
    
    'offline_title' => 'أنت غير متصل',
    'offline_subtitle' => 'يبدو أنك فقدت الاتصال بالإنترنت. لا تقلق، يمكنك الوصول إلى بعض ميزات لوحة الإدارة.',
    'offline_status' => 'غير متصل',
    'online_status' => 'متصل',
    
    'retry_connection' => 'إعادة المحاولة',
    'go_home' => 'الصفحة الرئيسية',
];
```

### 8. Update Composer Scripts

Remove old PWA-related scripts from your main project's `composer.json` if any.

### 9. Clean Up Routes

Remove any PWA-related routes from your `web.php` or `api.php` files, as the plugin handles them automatically.

### 10. Test the Integration

```bash
# Test the setup
php artisan filament-pwa:setup --validate

# Check that routes work
curl -I http://your-app.test/manifest.json
curl -I http://your-app.test/sw.js

# Test in browser
# Visit /admin and check for PWA installation prompt
# Check browser DevTools > Application > Manifest
# Check browser DevTools > Application > Service Workers
```

## Benefits of Migration

### Before (Custom Implementation)
- Manual PWA setup and maintenance
- Limited icon generation capabilities
- Basic service worker functionality
- No comprehensive testing
- Manual updates and improvements needed

### After (Plugin Implementation)
- Automatic PWA setup and maintenance
- Advanced icon generation with multiple formats
- Intelligent caching strategies
- Comprehensive test coverage
- Regular updates and improvements via Composer
- Better documentation and community support
- Standardized implementation across projects

## Customization Options

The plugin provides extensive customization options:

1. **Configuration-based**: Modify `config/filament-pwa.php`
2. **Environment-based**: Use `.env` variables
3. **Plugin-based**: Configure directly in the panel provider
4. **View-based**: Publish and customize views
5. **Translation-based**: Add custom translations

## Maintenance

### Updating the Plugin

```bash
composer update alareqi/filament-pwa
```

### Regenerating Icons

```bash
php artisan filament-pwa:setup --generate-icons --source=public/new-logo.svg
```

### Validating Setup

```bash
php artisan filament-pwa:setup --validate
```

## Troubleshooting

If you encounter issues during migration:

1. Clear all caches: `php artisan optimize:clear`
2. Validate PWA setup: `php artisan filament-pwa:setup --validate`
3. Check browser console for errors
4. Verify HTTPS in production
5. Test on different devices and browsers

## Support

For issues specific to the plugin:
- Check the plugin documentation
- Search existing issues on GitHub
- Create a new issue with detailed information

For integration issues:
- Verify all old PWA files are removed
- Check configuration matches your needs
- Test step by step following this guide
