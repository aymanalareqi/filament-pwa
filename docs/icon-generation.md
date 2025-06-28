# Icon Generation Guide

This guide covers everything you need to know about PWA icons and how to generate them using the Filament PWA plugin.

## Icon Requirements

PWAs require multiple icon sizes to work properly across different devices and platforms:

### Required Sizes
- **192x192** - Minimum required size for PWA installation
- **512x512** - Minimum required size for PWA installation

### Recommended Sizes
- **72x72** - Android Chrome
- **96x96** - Android Chrome
- **128x128** - Android Chrome
- **144x144** - Windows tiles
- **152x152** - iOS Safari
- **384x384** - Android Chrome

### Additional Sizes
- **16x16** - Browser favicon
- **32x32** - Browser favicon
- **70x70** - Windows tiles
- **150x150** - Windows tiles
- **310x310** - Windows tiles

### Maskable Icons
- **192x192** - Maskable version for adaptive icons
- **512x512** - Maskable version for adaptive icons

## Source Image Requirements

### SVG (Recommended)
- **Format**: SVG
- **Size**: Any size (vector format)
- **Benefits**: 
  - Highest quality output
  - Perfect scaling to any size
  - Smallest file size
  - Best for simple logos and icons

### Raster Images
- **Format**: PNG, JPG, or GIF
- **Minimum Size**: 512x512 pixels
- **Recommended Size**: 1024x1024 pixels or larger
- **Benefits**:
  - Works with complex images
  - Supports photographs
  - Widely supported

## Generating Icons

### Basic Generation

```bash
# Using SVG source (recommended)
php artisan filament-pwa:setup --generate-icons --source=public/logo.svg

# Using PNG source
php artisan filament-pwa:setup --generate-icons --source=public/logo.png

# Using absolute path
php artisan filament-pwa:setup --generate-icons --source=/path/to/your/logo.svg
```

### Configuration

You can configure icon generation in `config/filament-pwa.php`:

```php
'icons' => [
    'source_path' => env('PWA_ICON_SOURCE', 'icon.svg'),
    'output_path' => 'images/icons',
    'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
    'maskable_sizes' => [192, 512],
    'additional_sizes' => [16, 32, 70, 150, 310],
],
```

## Image Processing Libraries

The plugin supports multiple image processing libraries for optimal quality:

### 1. ImageMagick (Recommended)
- **Best quality** for SVG processing
- **Vector-based** scaling
- **Supports transparency**

Install ImageMagick:
```bash
# Ubuntu/Debian
sudo apt-get install php-imagick

# macOS with Homebrew
brew install imagemagick
brew install php-imagick

# Windows
# Download from https://windows.php.net/downloads/pecl/releases/imagick/
```

### 2. Intervention Image
- **Good quality** for raster images
- **Easy to install**
- **Supports multiple drivers**

Install Intervention Image:
```bash
composer require intervention/image
```

### 3. GD (Fallback)
- **Basic quality**
- **Built into most PHP installations**
- **Limited SVG support**

## Maskable Icons

Maskable icons are special versions that work with adaptive icon systems on Android. They include a "safe area" where the important parts of your icon are guaranteed to be visible.

### Safe Area
- The safe area is **80% of the icon size**
- The outer **20% may be masked** by the system
- Keep important elements within the safe area

### Generation
The plugin automatically generates maskable icons with:
- Theme color background
- Your icon centered in the safe area
- Proper padding to ensure visibility

## Output Structure

After generation, your icons will be organized as:

```
public/
├── images/
│   └── icons/
│       ├── icon-16x16.png
│       ├── icon-32x32.png
│       ├── icon-72x72.png
│       ├── icon-96x96.png
│       ├── icon-128x128.png
│       ├── icon-144x144.png
│       ├── icon-152x152.png
│       ├── icon-192x192.png
│       ├── icon-192x192-maskable.png
│       ├── icon-384x384.png
│       ├── icon-512x512.png
│       ├── icon-512x512-maskable.png
│       ├── icon-70x70.png
│       ├── icon-150x150.png
│       └── icon-310x310.png
└── favicon.ico
```

## Best Practices

### Design Guidelines
1. **Simple designs** work best for small sizes
2. **High contrast** ensures visibility
3. **Avoid fine details** that disappear at small sizes
4. **Use solid backgrounds** for better recognition
5. **Test at different sizes** to ensure readability

### Technical Guidelines
1. **Use SVG when possible** for best quality
2. **Provide high-resolution sources** (1024x1024+) for raster images
3. **Use transparent backgrounds** for flexibility
4. **Test on different devices** and platforms
5. **Keep file sizes reasonable** for faster loading

### Color Considerations
1. **Match your brand colors**
2. **Ensure good contrast** with various backgrounds
3. **Consider dark mode** compatibility
4. **Use your theme color** for maskable icon backgrounds

## Troubleshooting

### Common Issues

**Icons appear blurry**
- Use SVG source for vector graphics
- Increase source image resolution
- Install ImageMagick for better quality

**Icons not generating**
- Check source file exists and is readable
- Verify output directory permissions
- Install required image processing libraries

**Maskable icons look wrong**
- Ensure important elements are in the center 80%
- Check theme color configuration
- Verify source image has transparent background

### Validation

Validate your generated icons:

```bash
php artisan filament-pwa:setup --validate
```

This will check:
- All required icon sizes exist
- Icons are properly formatted
- Manifest references correct icons

## Manual Icon Creation

If you prefer to create icons manually:

1. Create icons in all required sizes
2. Place them in `public/images/icons/`
3. Follow the naming convention: `icon-{size}x{size}.png`
4. Create maskable versions: `icon-{size}x{size}-maskable.png`
5. Update your manifest configuration if needed

## Testing Icons

Test your icons across different platforms:

### Desktop
- Chrome: Install PWA and check app icon
- Edge: Install PWA and check app icon
- Firefox: Check manifest in developer tools

### Mobile
- Android Chrome: Install PWA and check home screen icon
- iOS Safari: Add to home screen and check icon
- Samsung Internet: Install PWA and check icon

### Tools
- [PWA Builder](https://www.pwabuilder.com/) - Test PWA compliance
- [Lighthouse](https://developers.google.com/web/tools/lighthouse) - PWA audit
- [Web App Manifest Validator](https://manifest-validator.appspot.com/) - Validate manifest

## Next Steps

- [Configuration](configuration.md) - Customize PWA settings
- [Customization](customization.md) - Customize views and behavior
- [Troubleshooting](troubleshooting.md) - Common issues and solutions
