# PWA Debug Banner Feature

## Overview

This document describes the implementation of the debug banner feature for the Filament PWA plugin, which allows the PWA installation banner to be displayed persistently during development, regardless of user dismissal actions or browser installation state.

## Problem Solved

During PWA development, testing the installation flow can be challenging because:

1. **Browser Installation State**: Once a PWA is installed, browsers typically don't show installation prompts again
2. **User Dismissal Logic**: If a user dismisses the banner, it's hidden for 7 days by default
3. **beforeinstallprompt Event**: This event may not fire consistently during development
4. **Testing Friction**: Developers need to clear browser storage or use different browsers repeatedly

The debug banner feature solves these issues by providing a development-only mode that bypasses all these restrictions.

## Implementation Details

### 1. Configuration Option Added

**Config File (`config/filament-pwa.php`):**
```php
'installation' => [
    'enabled' => env('PWA_INSTALLATION_ENABLED', true),
    'prompt_delay' => env('PWA_INSTALLATION_DELAY', 2000),
    'ios_instructions_delay' => env('PWA_IOS_INSTRUCTIONS_DELAY', 5000),
    
    // Debug-only option: Always show installation banner in debug mode
    'show_banner_in_debug' => env('PWA_SHOW_BANNER_IN_DEBUG', true),
],
```

**Environment Variable:**
```env
PWA_SHOW_BANNER_IN_DEBUG=true
```

### 2. Fluent API Methods Added

**FilamentPwaPlugin Class:**
```php
// Enable debug banner mode
public function enableDebugBanner(bool $enabled = true): static

// Disable debug banner mode  
public function disableDebugBanner(): static

// Updated installation method with debug parameter
public function installation(
    bool $enabled = true, 
    int $promptDelay = 2000, 
    int $iosInstructionsDelay = 5000, 
    ?bool $showBannerInDebug = null
): static
```

**Usage Examples:**
```php
FilamentPwaPlugin::make()
    ->enableDebugBanner()  // Enable debug mode
    ->disableDebugBanner() // Disable debug mode
    
    // Or configure via installation method
    ->installation(
        enabled: true,
        promptDelay: 2000,
        iosInstructionsDelay: 5000,
        showBannerInDebug: true
    )
```

### 3. Installation Script Logic Updated

**Modified `resources/views/installation-script.blade.php`:**

The script now includes debug mode checks in two key methods:

**createInstallBanner():**
```javascript
createInstallBanner() {
    // Check if we should show banner in debug mode
    const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
    const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;
    
    // In debug mode, bypass installation and dismissal checks if debug banner is enabled
    if (isDebugMode && showBannerInDebug) {
        console.log('[PWA] Debug mode: Showing installation banner regardless of state');
    } else {
        // Normal logic: Don't show banner if already installed or disabled
        if (this.isInstalled || !this.config.installation_prompts?.enabled) return;
    }
    
    // Create banner...
}
```

**showInstallBanner():**
```javascript
showInstallBanner() {
    if (!this.banner) return;

    const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
    const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;
    
    // In debug mode, bypass all checks if debug banner is enabled
    if (isDebugMode && showBannerInDebug) {
        console.log('[PWA] Debug mode: Bypassing dismissal and installation checks');
        const delay = this.config.installation?.prompt_delay || 2000;
        setTimeout(() => {
            this.banner.classList.add('show');
        }, delay);
        return;
    }

    // Normal logic: Check installation status and dismissal
    if (this.isInstalled) return;
    
    const dismissed = localStorage.getItem('pwa-banner-dismissed');
    if (dismissed && Date.now() - parseInt(dismissed) < 7 * 24 * 60 * 60 * 1000) {
        return;
    }

    // Show banner normally...
}
```

**Initialization Logic:**
```javascript
init() {
    // ... existing code ...
    
    // Create install banner
    this.createInstallBanner();

    // In debug mode, show banner immediately if enabled
    const isDebugMode = {{ config('app.debug') ? 'true' : 'false' }};
    const showBannerInDebug = this.config.installation?.show_banner_in_debug ?? true;
    if (isDebugMode && showBannerInDebug && this.banner) {
        console.log('[PWA] Debug mode: Showing banner immediately');
        this.showInstallBanner();
    }
    
    // ... rest of initialization ...
}
```

### 4. Service Configuration Updated

**PwaService Default Config:**
```php
'installation' => [
    'enabled' => true,
    'prompt_delay' => 2000,
    'ios_instructions_delay' => 5000,
    'show_banner_in_debug' => true, // Added debug option
],
```

## Behavior in Different Modes

### Production Mode (`APP_DEBUG=false`)
- Debug banner setting is completely ignored
- Normal PWA installation logic applies
- Banner respects user dismissal and installation state
- No debug console messages

### Debug Mode (`APP_DEBUG=true`)

**With `show_banner_in_debug=true` (default):**
- ✅ Banner always shows, regardless of installation state
- ✅ Banner ignores user dismissal localStorage
- ✅ Banner shows immediately on page load
- ✅ Banner bypasses `beforeinstallprompt` event requirement
- ✅ Debug console messages logged
- ✅ Users can still interact with banner (install/dismiss)

**With `show_banner_in_debug=false`:**
- ❌ Normal PWA logic applies even in debug mode
- ❌ Banner respects dismissal and installation state

## Security & Safety

### Production Safety
- Feature only activates when `config('app.debug')` is `true`
- No impact on production environments
- Environment variable control for additional safety

### Development Benefits
- Immediate banner visibility for testing
- No need to clear browser storage repeatedly
- Consistent testing experience across different browsers
- Easy toggle via configuration

## Testing

### Manual Testing
1. Set `APP_DEBUG=true` in `.env`
2. Set `PWA_SHOW_BANNER_IN_DEBUG=true` in `.env`
3. Visit the admin panel
4. Banner should appear immediately
5. Dismiss banner and refresh - it should appear again
6. Install PWA and refresh - banner should still appear

### Automated Testing
A test file `tests/debug-banner-test.html` is provided to simulate the debug banner logic with different configuration combinations.

## Configuration Examples

### Enable Debug Banner (Recommended for Development)
```php
// config/filament-pwa.php
'installation' => [
    'enabled' => true,
    'show_banner_in_debug' => true,
],
```

```php
// Using fluent API
FilamentPwaPlugin::make()
    ->enableDebugBanner()
```

```env
# .env
APP_DEBUG=true
PWA_SHOW_BANNER_IN_DEBUG=true
```

### Disable Debug Banner
```php
// config/filament-pwa.php
'installation' => [
    'enabled' => true,
    'show_banner_in_debug' => false,
],
```

```php
// Using fluent API
FilamentPwaPlugin::make()
    ->disableDebugBanner()
```

```env
# .env
PWA_SHOW_BANNER_IN_DEBUG=false
```

## Benefits

### For Developers
- **Faster Testing**: No need to clear storage or switch browsers
- **Consistent Experience**: Banner always visible during development
- **Easy Debugging**: Clear console messages about banner state
- **Flexible Control**: Can be toggled via config or environment variables

### For Production
- **Zero Impact**: Feature completely disabled in production
- **Safe Deployment**: No risk of persistent banners in live environments
- **Normal Behavior**: Standard PWA installation flow maintained

## Conclusion

The debug banner feature significantly improves the developer experience when working with PWA installation flows, providing a reliable way to test and debug the installation banner without the friction typically associated with PWA development. The implementation is safe, production-aware, and easily configurable through multiple methods.
