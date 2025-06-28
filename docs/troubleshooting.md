# Troubleshooting Guide

This guide helps you resolve common issues with the Filament PWA plugin.

## Installation Issues

### Plugin Not Loading

**Problem**: Plugin doesn't appear to be working after installation.

**Solutions**:
1. Clear application cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

2. Verify plugin registration:
   ```php
   // In your Panel provider
   ->plugin(FilamentPwaPlugin::make())
   ```

3. Check for conflicts with other plugins

### Configuration Not Applied

**Problem**: Configuration changes don't take effect.

**Solutions**:
1. Clear config cache:
   ```bash
   php artisan config:clear
   ```

2. Verify configuration file location:
   ```bash
   ls -la config/filament-pwa.php
   ```

3. Check environment variables in `.env`

## Icon Generation Issues

### Icons Not Generating

**Problem**: `php artisan filament-pwa:setup --generate-icons` fails.

**Solutions**:
1. Check source file exists:
   ```bash
   ls -la public/icon.svg
   ```

2. Verify file permissions:
   ```bash
   chmod 644 public/icon.svg
   chmod 755 public/images/icons/
   ```

3. Install required image libraries:
   ```bash
   # ImageMagick
   sudo apt-get install php-imagick
   
   # Intervention Image
   composer require intervention/image
   ```

### Poor Icon Quality

**Problem**: Generated icons are blurry or low quality.

**Solutions**:
1. Use SVG source for best quality
2. Install ImageMagick extension
3. Use high-resolution source (1024x1024+) for raster images
4. Check source image quality

### Permission Errors

**Problem**: Cannot write to icons directory.

**Solutions**:
1. Create directory with proper permissions:
   ```bash
   mkdir -p public/images/icons
   chmod 755 public/images/icons
   ```

2. Check web server user permissions:
   ```bash
   chown -R www-data:www-data public/images/icons
   ```

## PWA Installation Issues

### Installation Prompt Not Showing

**Problem**: PWA installation banner doesn't appear.

**Solutions**:
1. Check HTTPS requirement (required in production)
2. Verify all PWA criteria are met:
   ```bash
   php artisan filament-pwa:setup --validate
   ```

3. Check browser console for errors
4. Clear browser cache and service worker
5. Verify manifest is accessible: `/manifest.json`

### Installation Fails

**Problem**: PWA installation fails or doesn't work properly.

**Solutions**:
1. Check manifest validation:
   - Visit Chrome DevTools > Application > Manifest
   - Use [Web App Manifest Validator](https://manifest-validator.appspot.com/)

2. Verify required icons exist:
   ```bash
   ls -la public/images/icons/icon-192x192.png
   ls -la public/images/icons/icon-512x512.png
   ```

3. Check service worker registration:
   - Visit Chrome DevTools > Application > Service Workers
   - Look for registration errors

### iOS Installation Issues

**Problem**: Installation doesn't work on iOS Safari.

**Solutions**:
1. iOS requires manual installation via "Add to Home Screen"
2. Ensure proper meta tags are present:
   ```html
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="apple-mobile-web-app-title" content="Your App">
   ```

3. Verify apple-touch-icon is present
4. Check iOS-specific requirements in documentation

## Service Worker Issues

### Service Worker Not Registering

**Problem**: Service worker fails to register.

**Solutions**:
1. Check service worker is accessible: `/sw.js`
2. Verify HTTPS in production
3. Check browser console for errors
4. Ensure proper MIME type (application/javascript)

### Caching Issues

**Problem**: Content not caching or updating properly.

**Solutions**:
1. Update cache version in configuration:
   ```php
   'cache_name' => 'filament-admin-v1.0.1',
   ```

2. Clear service worker cache:
   - Chrome DevTools > Application > Storage > Clear storage

3. Check cache patterns in service worker
4. Verify network requests in DevTools

### Offline Functionality Not Working

**Problem**: App doesn't work offline.

**Solutions**:
1. Verify service worker is active
2. Check cached resources in DevTools
3. Test offline page: `/offline`
4. Verify cache strategies are correct

## Manifest Issues

### Manifest Not Loading

**Problem**: Browser can't load manifest.json.

**Solutions**:
1. Check manifest is accessible:
   ```bash
   curl -I https://yoursite.com/manifest.json
   ```

2. Verify proper content-type header:
   ```
   Content-Type: application/json
   ```

3. Check for JSON syntax errors:
   ```bash
   php artisan filament-pwa:setup --validate
   ```

### Invalid Manifest

**Problem**: Manifest validation fails.

**Solutions**:
1. Use manifest validator tools
2. Check required fields are present:
   - name
   - short_name
   - start_url
   - display
   - icons

3. Verify icon paths are correct
4. Check JSON syntax

## Performance Issues

### Slow Loading

**Problem**: PWA loads slowly.

**Solutions**:
1. Optimize icon file sizes
2. Review cache strategies
3. Minimize service worker code
4. Use appropriate cache headers

### Large Bundle Size

**Problem**: PWA assets are too large.

**Solutions**:
1. Optimize images and icons
2. Remove unused cache patterns
3. Minimize JavaScript code
4. Use compression (gzip/brotli)

## Browser-Specific Issues

### Chrome Issues

**Problem**: PWA doesn't work properly in Chrome.

**Solutions**:
1. Check Chrome DevTools > Application tab
2. Verify Lighthouse PWA audit passes
3. Clear Chrome data for the site
4. Check Chrome flags that might interfere

### Firefox Issues

**Problem**: PWA features limited in Firefox.

**Solutions**:
1. Firefox has limited PWA support
2. Focus on service worker functionality
3. Test installation manually
4. Check Firefox-specific requirements

### Safari Issues

**Problem**: PWA doesn't work in Safari.

**Solutions**:
1. Safari requires iOS 11.3+ for PWA support
2. Use "Add to Home Screen" manually
3. Verify apple-specific meta tags
4. Check iOS-specific icon requirements

## Development Issues

### Local Development

**Problem**: PWA features don't work in local development.

**Solutions**:
1. Use HTTPS in local development:
   - Laravel Valet (automatic HTTPS)
   - ngrok for tunneling
   - Local SSL certificates

2. Use localhost (exempt from HTTPS requirement)
3. Test on actual devices when possible

### Testing Environment

**Problem**: PWA works locally but not in staging/production.

**Solutions**:
1. Verify HTTPS is properly configured
2. Check SSL certificate validity
3. Verify all assets are accessible
4. Test with browser developer tools

## Debugging Tools

### Browser DevTools

1. **Chrome DevTools**:
   - Application > Manifest
   - Application > Service Workers
   - Lighthouse > PWA audit

2. **Firefox DevTools**:
   - Application > Manifest
   - Application > Service Workers

### Command Line Tools

```bash
# Validate PWA setup
php artisan filament-pwa:setup --validate

# Check icon generation
php artisan filament-pwa:setup --generate-icons --source=public/logo.svg

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Online Tools

1. [PWA Builder](https://www.pwabuilder.com/) - PWA testing and validation
2. [Lighthouse](https://developers.google.com/web/tools/lighthouse) - PWA audit
3. [Web App Manifest Validator](https://manifest-validator.appspot.com/) - Manifest validation
4. [PWA Testing Tool](https://www.webpagetest.org/) - Performance testing

## Common Error Messages

### "Failed to register service worker"

**Cause**: Service worker file not accessible or invalid.

**Solution**: 
1. Check `/sw.js` is accessible
2. Verify HTTPS in production
3. Check service worker syntax

### "Manifest start_url is not in scope"

**Cause**: start_url is outside the defined scope.

**Solution**: 
1. Ensure start_url begins with scope
2. Update scope to include start_url
3. Check configuration values

### "No matching service worker detected"

**Cause**: Service worker not properly registered.

**Solution**:
1. Clear browser cache
2. Unregister existing service workers
3. Check registration code

## Getting Help

If you're still experiencing issues:

1. **Check the documentation** for detailed guides
2. **Search existing issues** on GitHub
3. **Create a new issue** with:
   - Laravel version
   - Filament version
   - Plugin version
   - Browser and version
   - Steps to reproduce
   - Error messages
   - Configuration details

4. **Join the community** for support and discussions

## Prevention Tips

1. **Always validate** after making changes
2. **Test on multiple browsers** and devices
3. **Use version control** for configuration changes
4. **Monitor PWA metrics** in production
5. **Keep dependencies updated**
6. **Follow PWA best practices**

## Next Steps

- [Configuration](configuration.md) - Review all configuration options
- [Customization](customization.md) - Customize views and behavior
- [Icon Generation](icon-generation.md) - Icon requirements and generation
