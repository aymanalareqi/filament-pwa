# Customization Guide

This guide covers how to customize the Filament PWA plugin to match your specific needs.

## View Customization

### Publishing Views

First, publish the views to customize them:

```bash
php artisan vendor:publish --tag="filament-pwa-views"
```

This creates customizable views in `resources/views/vendor/filament-pwa/`:

```
resources/views/vendor/filament-pwa/
├── meta-tags.blade.php
├── installation-script.blade.php
├── service-worker.blade.php
├── browserconfig.blade.php
└── offline.blade.php
```

### Meta Tags Customization

Edit `resources/views/vendor/filament-pwa/meta-tags.blade.php`:

```blade
{{-- Add custom meta tags --}}
<meta name="custom-property" content="custom-value">

{{-- Modify theme color dynamically --}}
<meta name="theme-color" content="{{ $config['theme_color'] }}">

{{-- Add custom splash screens --}}
<link rel="apple-touch-startup-image" 
      media="(device-width: 428px) and (device-height: 926px)" 
      href="{{ asset('images/splash/iphone-14-pro-max.png') }}">
```

### Installation Script Customization

Customize the installation behavior in `resources/views/vendor/filament-pwa/installation-script.blade.php`:

```javascript
// Custom installation prompt styling
const banner = document.createElement('div');
banner.className = 'custom-pwa-banner';
banner.innerHTML = `
    <div class="custom-banner-content">
        <h3>Install Our App</h3>
        <p>Get the best experience with our native-like app!</p>
        <button id="custom-install-btn">Install Now</button>
    </div>
`;

// Custom installation tracking
function trackCustomInstallation() {
    // Your custom analytics
    gtag('event', 'pwa_install', {
        event_category: 'PWA',
        event_label: 'Custom Install'
    });
}
```

### Service Worker Customization

Modify caching strategies in `resources/views/vendor/filament-pwa/service-worker.blade.php`:

```javascript
// Custom cache strategies
const CUSTOM_CACHE_PATTERNS = {
    api: /\/api\/v1\//,
    uploads: /\/uploads\//,
    vendor: /\/vendor\//,
};

// Custom background sync
self.addEventListener('sync', event => {
    if (event.tag === 'custom-sync') {
        event.waitUntil(doCustomSync());
    }
});

async function doCustomSync() {
    // Your custom sync logic
    const pendingRequests = await getPendingRequests();
    for (const request of pendingRequests) {
        await syncRequest(request);
    }
}
```

### Offline Page Customization

Customize the offline experience in `resources/views/vendor/filament-pwa/offline.blade.php`:

```blade
{{-- Custom offline page content --}}
<div class="custom-offline-container">
    <img src="{{ asset('images/offline-mascot.svg') }}" alt="Offline">
    <h1>{{ __('custom.offline_title') }}</h1>
    <p>{{ __('custom.offline_message') }}</p>
    
    {{-- Custom offline features --}}
    <div class="offline-features">
        <h3>What you can do offline:</h3>
        <ul>
            <li>View cached dashboard data</li>
            <li>Access recent reports</li>
            <li>Draft new content</li>
        </ul>
    </div>
    
    {{-- Custom actions --}}
    <div class="offline-actions">
        <button onclick="checkConnection()">Check Connection</button>
        <a href="/admin/cache">View Cached Data</a>
    </div>
</div>
```

## Plugin Configuration Customization

### Advanced Plugin Setup

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            FilamentPwaPlugin::make()
                ->appName(config('app.name') . ' Admin')
                ->shortName('Admin')
                ->description('Powerful admin interface')
                ->themeColor('#3B82F6')
                ->backgroundColor('#F8FAFC')
                ->startUrl('/admin/dashboard')
                ->displayMode('standalone')
                ->orientation('any')
                ->scope('/admin')
                ->language(app()->getLocale())
                ->direction(app()->isLocale('ar') ? 'rtl' : 'ltr')
                ->categories(['productivity', 'business', 'utilities'])
                ->shortcuts([
                    [
                        'name' => 'Quick Stats',
                        'short_name' => 'Stats',
                        'description' => 'View dashboard statistics',
                        'url' => '/admin/stats',
                        'icons' => [
                            [
                                'src' => '/images/icons/stats-96x96.png',
                                'sizes' => '96x96',
                            ],
                        ],
                    ],
                ])
                ->installationPrompts(true)
        );
}
```

### Dynamic Configuration

Create dynamic configuration based on user preferences:

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

public function panel(Panel $panel): Panel
{
    $user = auth()->user();
    $theme = $user?->preferred_theme ?? 'default';
    
    $themeColors = [
        'default' => '#A77B56',
        'blue' => '#3B82F6',
        'green' => '#10B981',
        'purple' => '#8B5CF6',
    ];

    return $panel
        ->plugin(
            FilamentPwaPlugin::make()
                ->themeColor($themeColors[$theme])
                ->language($user?->language ?? 'en')
                ->direction($user?->text_direction ?? 'ltr')
        );
}
```

## Translation Customization

### Custom Translations

Create custom translation files:

```php
// resources/lang/en/custom-pwa.php
return [
    'install_title' => 'Install Our Amazing App',
    'install_description' => 'Experience the power of our admin panel as a native app!',
    'install_button' => 'Install Now',
    'offline_title' => 'You\'re Working Offline',
    'offline_message' => 'Don\'t worry, your work is automatically saved locally.',
];
```

Use custom translations in views:

```blade
<div class="pwa-install-title">{{ __('custom-pwa.install_title') }}</div>
<div class="pwa-install-description">{{ __('custom-pwa.install_description') }}</div>
```

### RTL Language Support

For RTL languages, customize the views:

```blade
{{-- In meta-tags.blade.php --}}
<html dir="{{ $config['dir'] }}" lang="{{ $config['lang'] }}">

{{-- Custom RTL styles --}}
<style>
    @if($config['dir'] === 'rtl')
    .pwa-install-banner {
        direction: rtl;
        text-align: right;
    }
    
    .pwa-install-actions {
        flex-direction: row-reverse;
    }
    @endif
</style>
```

## Service Worker Customization

### Custom Caching Strategies

```javascript
// Network First for API calls
async function handleApiRequest(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        throw error;
    }
}

// Stale While Revalidate for images
async function handleImageRequest(request) {
    const cachedResponse = await caches.match(request);
    const fetchPromise = fetch(request).then(response => {
        if (response.ok) {
            const cache = caches.open(CACHE_NAME);
            cache.then(c => c.put(request, response.clone()));
        }
        return response;
    });
    
    return cachedResponse || fetchPromise;
}
```

### Background Sync

```javascript
// Register background sync
self.addEventListener('sync', event => {
    if (event.tag === 'form-submission') {
        event.waitUntil(syncFormSubmissions());
    }
});

async function syncFormSubmissions() {
    const submissions = await getStoredSubmissions();
    for (const submission of submissions) {
        try {
            await fetch(submission.url, {
                method: submission.method,
                body: submission.data,
                headers: submission.headers,
            });
            await removeStoredSubmission(submission.id);
        } catch (error) {
            console.error('Failed to sync submission:', error);
        }
    }
}
```

### Push Notifications

```javascript
// Handle push notifications
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    
    const options = {
        body: data.body || 'New notification',
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/icon-96x96.png',
        vibrate: [100, 50, 100],
        data: data,
        actions: [
            {
                action: 'view',
                title: 'View',
                icon: '/images/icons/view.png'
            },
            {
                action: 'dismiss',
                title: 'Dismiss',
                icon: '/images/icons/dismiss.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'Admin Panel', options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
    event.notification.close();
    
    if (event.action === 'view') {
        event.waitUntil(
            clients.openWindow(event.notification.data.url || '/admin')
        );
    }
});
```

## Styling Customization

### Custom CSS

Add custom styles to your admin panel:

```css
/* Custom PWA installation banner */
.pwa-install-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px 12px 0 0;
    box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.2);
}

.pwa-install-btn.primary {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Custom offline page */
.offline-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border: 2px solid #e2e8f0;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .pwa-install-banner {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        color: #f7fafc;
    }
    
    .offline-container {
        background: #1a202c;
        color: #f7fafc;
        border-color: #4a5568;
    }
}
```

### Theme Integration

Integrate with your existing theme:

```php
// In your panel provider
->colors([
    'primary' => Color::hex('#3B82F6'),
])
->plugin(
    FilamentPwaPlugin::make()
        ->themeColor('#3B82F6') // Match your primary color
        ->backgroundColor('#F8FAFC') // Match your background
)
```

## Advanced Customization

### Custom Manifest Generation

Create a custom manifest controller:

```php
use Alareqi\FilamentPwa\Services\PwaService;

class CustomPwaController extends Controller
{
    public function manifest()
    {
        $config = PwaService::getConfig();
        
        // Add custom manifest properties
        $config['custom_property'] = 'custom_value';
        $config['share_target'] = [
            'action' => '/admin/share',
            'method' => 'POST',
            'params' => [
                'title' => 'title',
                'text' => 'text',
                'url' => 'url',
            ],
        ];
        
        return response()->json($config);
    }
}
```

### Custom Installation Logic

```javascript
class CustomPWAInstaller extends PWAInstaller {
    constructor() {
        super();
        this.customConfig = window.customPWAConfig || {};
    }
    
    createInstallBanner() {
        // Your custom banner creation logic
        if (this.customConfig.useCustomBanner) {
            this.createCustomBanner();
        } else {
            super.createInstallBanner();
        }
    }
    
    createCustomBanner() {
        // Custom banner implementation
        const banner = this.createAdvancedBanner();
        document.body.appendChild(banner);
        this.banner = banner;
    }
}

// Initialize custom installer
new CustomPWAInstaller();
```

## Testing Customizations

### Local Testing

1. **Clear cache** after making changes
2. **Unregister service worker** in DevTools
3. **Test installation flow** on different devices
4. **Validate manifest** using browser tools

### Validation Commands

```bash
# Validate PWA setup
php artisan filament-pwa:setup --validate

# Test icon generation
php artisan filament-pwa:setup --generate-icons --source=public/test-logo.svg

# Publish and test views
php artisan vendor:publish --tag="filament-pwa-views" --force
```

## Next Steps

- [Troubleshooting](troubleshooting.md) - Common issues and solutions
- [Configuration](configuration.md) - All configuration options
- [Icon Generation](icon-generation.md) - Icon requirements and generation
