# Dynamic Configuration Examples

This document provides comprehensive examples of using closures for dynamic PWA configuration in the Filament PWA plugin.

## Overview

The Filament PWA plugin supports closures for dynamic configuration that is evaluated at runtime. This allows you to create configurations that adapt based on:

- User preferences and settings
- Environment variables
- Database settings
- User permissions and roles
- Application state
- Request context

## Basic Examples

### User-Based Configuration

```php
use Alareqi\FilamentPwa\FilamentPwaPlugin;

FilamentPwaPlugin::make()
    // App name based on user's company
    ->name(fn() => auth()->user()?->company_name . ' Admin' ?? 'Admin Panel')
    
    // Theme color from user preferences
    ->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')
    
    // Language from user profile
    ->language(fn() => auth()->user()?->language ?? app()->getLocale())
    
    // Text direction based on language
    ->direction(fn() => in_array(auth()->user()?->language ?? 'en', ['ar', 'he', 'fa']) ? 'rtl' : 'ltr')
    
    // Start URL based on user role
    ->startUrl(fn() => match(auth()->user()?->role) {
        'admin' => '/admin',
        'manager' => '/admin/dashboard',
        'user' => '/admin/profile',
        default => '/admin/login',
    })
```

### Environment-Based Configuration

```php
FilamentPwaPlugin::make()
    // Different app names per environment
    ->name(fn() => match(config('app.env')) {
        'production' => config('app.name') . ' Admin',
        'staging' => config('app.name') . ' Admin (Staging)',
        'local' => config('app.name') . ' Admin (Local)',
        default => config('app.name') . ' Admin (Dev)',
    })
    
    // Debug information in development
    ->description(fn() => config('app.debug') 
        ? 'Admin panel with debug mode enabled - Version ' . config('app.version', '1.0.0')
        : 'Professional admin panel for ' . config('app.name')
    )
    
    // Different theme colors per environment
    ->themeColor(fn() => match(config('app.env')) {
        'production' => '#10B981', // Green for production
        'staging' => '#F59E0B',    // Orange for staging
        'local' => '#3B82F6',      // Blue for local
        default => '#EF4444',      // Red for other environments
    })
```

## Advanced Examples

### Permission-Based Shortcuts

```php
FilamentPwaPlugin::make()
    // Dashboard shortcut (always available)
    ->addShortcut('Dashboard', '/admin', 'Go to dashboard')
    
    // Users shortcut (only if user can manage users)
    ->addShortcut(fn() => auth()->user()?->can('manage-users') ? [
        'name' => 'Users',
        'short_name' => 'Users',
        'description' => 'Manage system users',
        'url' => '/admin/users',
        'icons' => [
            [
                'src' => '/images/icons/users.png',
                'sizes' => '96x96',
            ],
        ],
    ] : null) // Return null to skip this shortcut
    
    // Reports shortcut (only for admins)
    ->addShortcut(fn() => auth()->user()?->isAdmin() ? [
        'name' => 'Reports',
        'short_name' => 'Reports',
        'description' => 'View system reports',
        'url' => '/admin/reports',
        'icons' => [
            [
                'src' => '/images/icons/reports.png',
                'sizes' => '96x96',
            ],
        ],
    ] : null)
    
    // Settings shortcut (only for super admins)
    ->addShortcut(fn() => auth()->user()?->hasRole('super-admin') ? [
        'name' => 'Settings',
        'short_name' => 'Settings',
        'description' => 'System settings',
        'url' => '/admin/settings',
        'icons' => [
            [
                'src' => '/images/icons/settings.png',
                'sizes' => '96x96',
            ],
        ],
    ] : null)
```

### Database-Driven Configuration

```php
use App\Models\AppSetting;

FilamentPwaPlugin::make()
    // App name from database settings
    ->name(fn() => AppSetting::getValue('pwa_app_name', config('app.name') . ' Admin'))
    
    // Theme color from database
    ->themeColor(fn() => AppSetting::getValue('pwa_theme_color', '#3B82F6'))
    
    // Background color from database
    ->backgroundColor(fn() => AppSetting::getValue('pwa_background_color', '#ffffff'))
    
    // Installation prompts based on database setting
    ->installation(
        enabled: fn() => AppSetting::getValue('pwa_installation_enabled', true),
        promptDelay: fn() => AppSetting::getValue('pwa_installation_delay', 2000)
    )
```

### Multi-Tenant Configuration

```php
FilamentPwaPlugin::make()
    // Tenant-specific app name
    ->name(fn() => tenant()?->name . ' Admin' ?? 'Admin Panel')
    
    // Tenant-specific theme color
    ->themeColor(fn() => tenant()?->theme_color ?? '#3B82F6')
    
    // Tenant-specific start URL
    ->startUrl(fn() => tenant() ? '/admin/' . tenant()->slug : '/admin')
    
    // Tenant-specific shortcuts
    ->addShortcut(fn() => [
        'name' => 'Dashboard',
        'short_name' => 'Dashboard',
        'description' => 'Go to ' . (tenant()?->name ?? 'main') . ' dashboard',
        'url' => tenant() ? '/admin/' . tenant()->slug : '/admin',
        'icons' => [
            [
                'src' => tenant()?->icon_url ?? '/images/icons/icon-96x96.png',
                'sizes' => '96x96',
            ],
        ],
    ])
```

### Time-Based Configuration

```php
FilamentPwaPlugin::make()
    // Different theme colors based on time of day
    ->themeColor(fn() => match(true) {
        now()->hour >= 6 && now()->hour < 12 => '#F59E0B',  // Morning - Orange
        now()->hour >= 12 && now()->hour < 18 => '#3B82F6', // Afternoon - Blue
        now()->hour >= 18 && now()->hour < 22 => '#8B5CF6', // Evening - Purple
        default => '#1F2937', // Night - Dark Gray
    })
    
    // Greeting in description based on time
    ->description(fn() => match(true) {
        now()->hour >= 5 && now()->hour < 12 => 'Good morning! Welcome to your admin panel.',
        now()->hour >= 12 && now()->hour < 17 => 'Good afternoon! Manage your application.',
        now()->hour >= 17 && now()->hour < 22 => 'Good evening! Check your dashboard.',
        default => 'Welcome to your admin panel.',
    })
```

### Feature Flag Configuration

```php
use App\Services\FeatureFlagService;

FilamentPwaPlugin::make()
    // Conditional shortcuts based on feature flags
    ->addShortcut(fn() => FeatureFlagService::isEnabled('analytics') ? [
        'name' => 'Analytics',
        'short_name' => 'Analytics',
        'description' => 'View analytics dashboard',
        'url' => '/admin/analytics',
        'icons' => [['src' => '/images/icons/analytics.png', 'sizes' => '96x96']],
    ] : null)
    
    // Beta features shortcut
    ->addShortcut(fn() => FeatureFlagService::isEnabled('beta_features') && auth()->user()?->is_beta_tester ? [
        'name' => 'Beta Features',
        'short_name' => 'Beta',
        'description' => 'Try new beta features',
        'url' => '/admin/beta',
        'icons' => [['src' => '/images/icons/beta.png', 'sizes' => '96x96']],
    ] : null)
```

## Best Practices

### 1. Performance Considerations

```php
// ✅ Good: Cache expensive operations
->name(fn() => cache()->remember('pwa_app_name', 3600, fn() => 
    auth()->user()?->company->name . ' Admin' ?? 'Admin Panel'
))

// ❌ Avoid: Expensive database queries on every request
->name(fn() => auth()->user()?->company()->with('settings')->first()?->name . ' Admin')
```

### 2. Error Handling

```php
// ✅ Good: Provide fallbacks
->themeColor(fn() => auth()->user()?->theme_color ?? '#3B82F6')

// ✅ Good: Handle exceptions
->name(fn() => {
    try {
        return auth()->user()?->company_name . ' Admin';
    } catch (\Exception $e) {
        return 'Admin Panel';
    }
})
```

### 3. Null Handling for Shortcuts

```php
// ✅ Good: Return null to skip shortcuts
->addShortcut(fn() => auth()->user()?->can('manage-users') ? [
    'name' => 'Users',
    'url' => '/admin/users',
    // ... other properties
] : null)

// ✅ Good: Use early returns
->addShortcut(fn() => {
    if (!auth()->user()?->can('manage-users')) {
        return null;
    }
    
    return [
        'name' => 'Users',
        'url' => '/admin/users',
        // ... other properties
    ];
})
```

### 4. Type Safety

```php
// ✅ Good: Ensure return types match expectations
->language(fn(): string => auth()->user()?->language ?? 'en')
->direction(fn(): string => auth()->user()?->isRtl() ? 'rtl' : 'ltr')
```

## Migration from Static Configuration

If you're migrating from static language-specific methods:

```php
// ❌ Old approach (removed)
->english()
->arabic()
->french()

// ✅ New approach
->language(fn() => auth()->user()?->language ?? 'en')
->direction(fn() => in_array(auth()->user()?->language ?? 'en', ['ar', 'he', 'fa']) ? 'rtl' : 'ltr')

// ✅ Or create your own helper methods
->language(fn() => $this->getUserLanguage())
->direction(fn() => $this->getUserDirection())
```

## Conclusion

Dynamic configuration with closures provides powerful flexibility for creating adaptive PWA configurations. Use them to create personalized, context-aware experiences while maintaining good performance and error handling practices.
