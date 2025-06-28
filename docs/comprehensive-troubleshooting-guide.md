# Comprehensive Troubleshooting Guide

This guide helps you resolve common issues with the Filament PWA plugin, including installation problems, configuration issues, and performance optimization.

## Table of Contents

- [Common Issues](#common-issues)
- [Installation Problems](#installation-problems)
- [Configuration Issues](#configuration-issues)
- [Performance Issues](#performance-issues)
- [Validation Errors](#validation-errors)
- [Debug Tools](#debug-tools)
- [Environment-Specific Issues](#environment-specific-issues)
- [Browser-Specific Issues](#browser-specific-issues)
- [Getting Help](#getting-help)

## Common Issues

### Installation Prompt Not Showing

**Problem**: The PWA installation prompt doesn't appear.

**Symptoms**:
- No installation banner visible
- Browser doesn't show "Add to Home Screen" option
- PWA installation criteria not met

**Solutions**:

1. **Check HTTPS requirement**:
   ```bash
   # PWAs require HTTPS in production
   # For development, use localhost or 127.0.0.1
   php artisan serve --host=localhost
   ```

2. **Enable debug mode**:
   ```php
   FilamentPwaPlugin::make()
       ->enableDebugBanner()  // Always show in debug mode
   ```

3. **Check browser console**:
   - Open Developer Tools → Console
   - Look for PWA-related errors
   - Check if service worker is registered

4. **Validate PWA setup**:
   ```bash
   php artisan filament-pwa:setup --validate
   ```

5. **Check installation criteria**:
   - Manifest file exists and is valid
   - Service worker is registered
   - Required icons (192x192, 512x512) exist
   - HTTPS is enabled (production)
   - Site is not already installed

### Icons Not Loading

**Problem**: PWA icons are not displaying correctly.

**Symptoms**:
- Broken image icons in browser
- Default browser icons instead of custom icons
- 404 errors for icon files

**Solutions**:

1. **Regenerate icons**:
   ```bash
   php artisan filament-pwa:setup --generate-icons --source=public/logo.svg
   ```

2. **Check file permissions**:
   ```bash
   chmod 755 public/images/icons
   chmod 644 public/images/icons/*.png
   ```

3. **Verify icon paths**:
   ```bash
   # Check if icons exist
   ls -la public/images/icons/
   
   # Expected files:
   # icon-72x72.png, icon-96x96.png, icon-128x128.png, etc.
   # icon-192x192-maskable.png, icon-512x512-maskable.png
   ```

4. **Check icon configuration**:
   ```php
   // Verify icon path in config
   'icons' => [
       'path' => 'images/icons',  // Should match actual directory
   ],
   ```

5. **Clear browser cache**:
   - Hard refresh (Ctrl+F5 or Cmd+Shift+R)
   - Clear application cache in DevTools

### Service Worker Issues

**Problem**: Service worker not updating or caching properly.

**Symptoms**:
- Old content still showing after updates
- Offline functionality not working
- Service worker registration errors

**Solutions**:

1. **Update cache name**:
   ```php
   'service_worker' => [
       'cache_name' => 'my-app-v1.0.1',  // Increment version
   ],
   ```

2. **Clear service worker**:
   - DevTools → Application → Service Workers
   - Click "Unregister" and refresh

3. **Check service worker registration**:
   ```javascript
   // In browser console
   navigator.serviceWorker.getRegistrations().then(function(registrations) {
       console.log(registrations);
   });
   ```

4. **Force service worker update**:
   ```javascript
   // In browser console
   navigator.serviceWorker.getRegistrations().then(function(registrations) {
       registrations.forEach(function(registration) {
           registration.update();
       });
   });
   ```

### PWA Not Installable

**Problem**: Browser doesn't show install option.

**Symptoms**:
- No "Add to Home Screen" option
- Installation prompt never appears
- PWA criteria not met

**Solutions**:

1. **Check PWA criteria**:
   ```bash
   php artisan filament-pwa:setup --validate
   ```

2. **Verify manifest**:
   - Visit `/manifest.json` in browser
   - Check for JSON syntax errors
   - Ensure all required fields are present

3. **Check required icons**:
   - Ensure 192x192 and 512x512 icons exist
   - Verify icon paths in manifest
   - Check icon file formats (PNG recommended)

4. **Test in different browsers**:
   - Chrome: DevTools → Application → Manifest
   - Firefox: about:debugging → Service Workers
   - Safari: Develop → Service Workers

5. **Check engagement heuristics**:
   - User must interact with the site
   - Site must be visited multiple times
   - Some browsers require time delay

## Installation Problems

### Package Installation Issues

**Problem**: Composer installation fails.

**Solutions**:

1. **Check PHP version**:
   ```bash
   php -v  # Ensure PHP 8.1+
   ```

2. **Update Composer**:
   ```bash
   composer self-update
   composer update
   ```

3. **Clear Composer cache**:
   ```bash
   composer clear-cache
   composer install
   ```

### Asset Publishing Issues

**Problem**: Assets not publishing correctly.

**Solutions**:

1. **Force publish assets**:
   ```bash
   php artisan vendor:publish --tag="filament-pwa-assets" --force
   php artisan vendor:publish --tag="filament-pwa-config" --force
   php artisan vendor:publish --tag="filament-pwa-views" --force
   php artisan vendor:publish --tag="filament-pwa-lang" --force
   ```

2. **Check file permissions**:
   ```bash
   chmod -R 755 public/
   chmod -R 755 config/
   chmod -R 755 resources/views/
   chmod -R 755 resources/lang/
   ```

3. **Verify published files**:
   ```bash
   ls -la public/manifest.json
   ls -la public/sw.js
   ls -la config/filament-pwa.php
   ls -la resources/lang/*/pwa.php
   ```

### Icon Generation Issues

**Problem**: Icon generation fails.

**Solutions**:

1. **Check source image**:
   ```bash
   # Ensure source image exists and is readable
   ls -la public/logo.svg
   file public/logo.svg  # Check file type
   ```

2. **Install image processing extensions**:
   ```bash
   # Ubuntu/Debian
   sudo apt-get install php-imagick php-gd
   
   # macOS with Homebrew
   brew install imagemagick
   brew install php-imagick
   
   # Windows
   # Download from https://windows.php.net/downloads/pecl/releases/imagick/
   ```

3. **Check extension availability**:
   ```php
   // Check in PHP
   var_dump(extension_loaded('imagick'));
   var_dump(extension_loaded('gd'));
   ```

4. **Use different source format**:
   ```bash
   # Try PNG instead of SVG
   php artisan filament-pwa:setup --generate-icons --source=public/logo.png
   ```

5. **Check memory limits**:
   ```php
   // php.ini
   memory_limit = 512M
   max_execution_time = 300
   ```

## Configuration Issues

### Theme Color Not Detected

**Problem**: PWA doesn't use Filament's theme color.

**Symptoms**:
- Default blue color instead of custom theme
- Browser UI doesn't match Filament colors

**Solutions**:

1. **Debug color detection**:
   ```php
   use Alareqi\FilamentPwa\Services\PwaService;
   
   $debug = PwaService::debugColorDetection();
   dd($debug);
   ```

2. **Manual configuration**:
   ```php
   FilamentPwaPlugin::make()
       ->themeColor('#3B82F6')
   ```

3. **Check Filament panel configuration**:
   ```php
   // Ensure Filament colors are properly configured
   $panel->colors([
       'primary' => Color::Indigo,
   ])
   ```

4. **Use environment variable**:
   ```env
   PWA_THEME_COLOR="#3B82F6"
   ```

### Language Not Detected

**Problem**: PWA doesn't use correct language.

**Symptoms**:
- English text instead of expected language
- Wrong text direction for RTL languages

**Solutions**:

1. **Check Laravel locale**:
   ```php
   dd(app()->getLocale());
   ```

2. **Set locale in config**:
   ```php
   // config/app.php
   'locale' => 'ar',
   ```

3. **Manual PWA configuration**:
   ```php
   FilamentPwaPlugin::make()
       ->language('ar')
       ->rtl()
   ```

4. **Publish language files**:
   ```bash
   php artisan vendor:publish --tag="filament-pwa-lang"
   ```

5. **Check translation files exist**:
   ```bash
   ls -la resources/lang/ar/pwa.php
   ```

### Installation Prompts in Wrong Language

**Problem**: Installation prompts show in wrong language.

**Solutions**:

1. **Check translation files**:
   ```bash
   ls -la resources/lang/*/pwa.php
   ```

2. **Verify locale setting**:
   ```php
   // Check current locale
   dd(app()->getLocale());
   ```

3. **Clear translation cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Test with specific locale**:
   ```php
   // Temporarily set locale for testing
   app()->setLocale('ar');
   ```

5. **Check translation key structure**:
   ```php
   // Ensure translation files have correct structure
   return [
       'install_title' => 'Your translation',
       'validation' => [
           'manifest_missing' => 'Your translation',
       ],
   ];
   ```

## Performance Issues

### Slow Loading

**Problem**: PWA loads slowly.

**Symptoms**:
- Long loading times
- Poor user experience
- High bandwidth usage

**Solutions**:

1. **Optimize cache strategy**:
   ```php
   'service_worker' => [
       'cache_urls' => [
           '/admin',           // Only cache essential pages
           '/admin/login',
       ],
   ],
   ```

2. **Reduce icon sizes**:
   ```php
   'icons' => [
       'sizes' => [192, 512],  // Only generate essential sizes
   ],
   ```

3. **Optimize images**:
   ```bash
   # Use optimized source images
   # Compress PNG files
   # Use SVG for logos when possible
   ```

4. **Enable compression**:
   ```apache
   # .htaccess
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE application/json
       AddOutputFilterByType DEFLATE image/svg+xml
   </IfModule>
   ```

### Large Cache Size

**Problem**: Service worker cache is too large.

**Solutions**:

1. **Limit cached URLs**:
   ```php
   'service_worker' => [
       'cache_urls' => [
           '/admin',
           '/admin/login',
           // Remove unnecessary URLs
       ],
   ],
   ```

2. **Optimize cache patterns**:
   ```php
   'cache_patterns' => [
       'images' => '/\.(png|jpg|jpeg|webp)$/',  // Only cache images
       // Remove font caching if not needed
   ],
   ```

3. **Implement cache expiration**:
   ```javascript
   // Custom service worker modifications
   // Add cache expiration logic
   ```

### Memory Issues

**Problem**: High memory usage during icon generation.

**Solutions**:

1. **Increase PHP memory limit**:
   ```php
   // php.ini
   memory_limit = 512M
   ```

2. **Use smaller source images**:
   ```bash
   # Resize source image before generation
   # Recommended: 1024x1024 maximum
   ```

3. **Generate icons in batches**:
   ```bash
   # Generate specific sizes only
   php artisan filament-pwa:setup --generate-icons --source=logo.svg
   ```

## Validation Errors

### Manifest Missing

**Error**: `Web app manifest not found`

**Solutions**:

1. **Publish PWA assets**:
   ```bash
   php artisan vendor:publish --tag="filament-pwa-assets" --force
   ```

2. **Check manifest file**:
   ```bash
   ls -la public/manifest.json
   curl -I http://localhost/manifest.json
   ```

3. **Verify web server configuration**:
   ```apache
   # .htaccess - ensure JSON files are served correctly
   <Files "manifest.json">
       Header set Content-Type "application/manifest+json"
   </Files>
   ```

### Service Worker Missing

**Error**: `Service worker not found`

**Solutions**:

1. **Publish service worker**:
   ```bash
   php artisan vendor:publish --tag="filament-pwa-assets" --force
   ```

2. **Check service worker file**:
   ```bash
   ls -la public/sw.js
   curl -I http://localhost/sw.js
   ```

3. **Verify service worker registration**:
   ```javascript
   // Check in browser console
   navigator.serviceWorker.getRegistrations()
   ```

### Icons Missing

**Error**: `Required PWA icons not found`

**Solutions**:

1. **Generate icons**:
   ```bash
   php artisan filament-pwa:setup --generate-icons --source=public/logo.svg
   ```

2. **Check required icon sizes**:
   ```bash
   ls -la public/images/icons/icon-192x192.png
   ls -la public/images/icons/icon-512x512.png
   ```

3. **Verify icon paths in manifest**:
   ```json
   {
     "icons": [
       {
         "src": "/images/icons/icon-192x192.png",
         "sizes": "192x192",
         "type": "image/png"
       }
     ]
   }
   ```

### HTTPS Required

**Error**: `HTTPS is required for PWA in production`

**Solutions**:

1. **Enable HTTPS in production**:
   ```bash
   # Use SSL certificate
   # Configure web server for HTTPS
   ```

2. **For development**:
   ```bash
   # Use localhost (exempt from HTTPS requirement)
   php artisan serve --host=localhost

   # Or use Laravel Valet for automatic HTTPS
   valet secure your-site
   ```

3. **Check environment detection**:
   ```php
   // Ensure APP_ENV is set correctly
   APP_ENV=production  // Requires HTTPS
   APP_ENV=local       // HTTPS not required
   ```

## Debug Tools

### PWA Validation Command

```bash
php artisan filament-pwa:setup --validate
```

This command checks:
- Manifest file existence and validity
- Service worker registration
- Required icon files
- HTTPS requirements
- Basic PWA compliance

### Color Detection Debug

```php
use Alareqi\FilamentPwa\Services\PwaService;

$debug = PwaService::debugColorDetection();
dd($debug);
```

This provides information about:
- Detected theme color
- Filament panel availability
- Color configuration sources
- Final merged configuration

### Browser Developer Tools

1. **Chrome DevTools**:
   - Application → Manifest (check manifest)
   - Application → Service Workers (check SW status)
   - Application → Storage (check cached resources)
   - Lighthouse → PWA audit

2. **Firefox DevTools**:
   - about:debugging → Service Workers
   - Network → check for 404 errors
   - Application → Manifest

3. **Safari DevTools**:
   - Develop → Service Workers
   - Web Inspector → Storage
   - Console → check for errors

### Manual Testing

1. **Test installation flow**:
   ```javascript
   // Check if PWA is installable
   window.addEventListener('beforeinstallprompt', (e) => {
       console.log('PWA is installable');
   });
   ```

2. **Test offline functionality**:
   - Disconnect network
   - Navigate to cached pages
   - Check offline page

3. **Test service worker**:
   ```javascript
   // Check service worker status
   navigator.serviceWorker.ready.then((registration) => {
       console.log('Service Worker ready:', registration);
   });
   ```

## Environment-Specific Issues

### Development Environment

**Common issues**:
- HTTPS requirement (use localhost)
- Installation prompts not showing (enable debug mode)
- Service worker caching issues (disable cache in DevTools)

**Solutions**:
```php
FilamentPwaPlugin::make()
    ->enableDebugBanner()  // Always show installation prompts
```

```bash
# Use localhost for development
php artisan serve --host=localhost
```

### Production Environment

**Common issues**:
- HTTPS certificate issues
- Cache not updating
- Icons not loading (CDN issues)

**Solutions**:
- Ensure valid HTTPS certificate
- Update cache names for new deployments
- Verify asset URLs are correct
- Check CDN configuration

### Staging Environment

**Common issues**:
- Mixed content warnings
- Different domain configurations
- Authentication issues

**Solutions**:
- Use HTTPS for staging
- Update start_url and scope for staging domain
- Configure proper authentication

## Browser-Specific Issues

### Chrome/Chromium

**Common issues**:
- Installation criteria too strict
- Service worker update delays

**Solutions**:
- Check DevTools → Application → Manifest for errors
- Use "Update on reload" in Service Workers tab
- Clear site data if needed

### Firefox

**Common issues**:
- Limited PWA support
- Different installation behavior

**Solutions**:
- Check about:config for PWA settings
- Use about:debugging for service worker inspection

### Safari (iOS/macOS)

**Common issues**:
- Limited PWA features
- Manual installation required on iOS

**Solutions**:
- Provide iOS-specific installation instructions
- Ensure apple-touch-icon is present
- Test on actual iOS devices

### Edge

**Common issues**:
- Similar to Chrome but with slight differences

**Solutions**:
- Test specifically in Edge
- Check Microsoft Store integration

## Getting Help

If you're still experiencing issues:

1. **Check the GitHub issues**: [GitHub Issues](https://github.com/aymanalareqi/filament-pwa/issues)

2. **Create a detailed bug report** with:
   - Laravel version
   - Filament version
   - Plugin version
   - Browser and version
   - Operating system
   - Steps to reproduce
   - Error messages
   - Configuration used

3. **Include debug information**:
   ```bash
   php artisan filament-pwa:setup --validate
   php artisan about
   ```

4. **Test in multiple browsers** to isolate browser-specific issues

5. **Provide minimal reproduction case** when possible

6. **Check documentation** for recent updates and changes

### Useful Commands for Bug Reports

```bash
# System information
php artisan about

# PWA validation
php artisan filament-pwa:setup --validate

# Check published assets
ls -la public/manifest.json public/sw.js public/images/icons/

# Check configuration
php artisan config:show filament-pwa

# Check routes
php artisan route:list | grep manifest
```
