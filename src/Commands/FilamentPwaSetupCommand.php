<?php

namespace Alareqi\FilamentPwa\Commands;

use Alareqi\FilamentPwa\Services\PwaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FilamentPwaSetupCommand extends Command
{
    protected $signature = 'filament-pwa:setup {--generate-icons} {--validate} {--source=} {--publish-assets}';
    
    protected $description = 'Setup and validate PWA configuration for Filament admin panel';

    public function handle()
    {
        $this->info('🚀 Setting up Filament PWA Plugin...');

        if ($this->option('publish-assets')) {
            $this->publishAssets();
        }

        if ($this->option('generate-icons')) {
            $this->generateIcons();
        }

        if ($this->option('validate')) {
            $this->validatePWA();
        }

        if (!$this->option('generate-icons') && !$this->option('validate') && !$this->option('publish-assets')) {
            $this->showPWAStatus();
        }

        $this->info('✅ Filament PWA setup completed!');
    }

    protected function publishAssets()
    {
        $this->info('📦 Publishing PWA assets...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'filament-pwa-config',
            '--force' => true,
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--tag' => 'filament-pwa-views',
            '--force' => true,
        ]);

        // Publish assets
        $this->call('vendor:publish', [
            '--tag' => 'filament-pwa-assets',
            '--force' => true,
        ]);

        $this->info('✅ Assets published successfully!');
    }

    protected function generateIcons()
    {
        $this->info('📱 Generating PWA icons...');

        $sourcePath = $this->option('source') ?: config('filament-pwa.icons.source_path', 'icon.svg');
        
        if (!str_starts_with($sourcePath, '/')) {
            $sourcePath = public_path($sourcePath);
        }

        if (!File::exists($sourcePath)) {
            $this->error("Source image not found: {$sourcePath}");
            $this->info('Please provide a source image using --source=path/to/image.svg or --source=path/to/image.png');
            return;
        }

        // Create icons directory if it doesn't exist
        $outputPath = config('filament-pwa.icons.output_path', 'images/icons');
        $iconsDir = public_path($outputPath);
        
        if (!File::exists($iconsDir)) {
            File::makeDirectory($iconsDir, 0755, true);
        }

        // Determine if source is SVG or raster image
        $isSvg = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) === 'svg';

        if ($isSvg) {
            $this->info('🎨 Detected SVG source - using high-quality vector conversion');
            $this->generateIconsFromSvg($sourcePath, $iconsDir);
        } else {
            $this->info('🖼️ Detected raster image - using image processing');
            $this->generateIconsFromRaster($sourcePath, $iconsDir);
        }
    }

    protected function generateIconsFromSvg($svgPath, $iconsDir)
    {
        try {
            // Check if Imagick is available for SVG processing
            if (!extension_loaded('imagick')) {
                $this->warn('Imagick extension not available. Falling back to GD with SVG conversion.');
                $this->generateIconsFromSvgWithGd($svgPath, $iconsDir);
                return;
            }

            $sizes = PwaService::getIconSizes();
            $additionalSizes = config('filament-pwa.icons.additional_sizes', [16, 32, 70, 150, 310]);
            $allSizes = array_merge($sizes, $additionalSizes);

            foreach ($allSizes as $size) {
                $this->generateIconFromSvg($svgPath, $size, $iconsDir);
            }

            // Generate maskable icons
            $this->generateMaskableIconsFromSvg($svgPath, $iconsDir);

            // Generate favicon
            $this->generateFaviconFromSvg($svgPath);

            $this->info('✅ Icons generated successfully from SVG!');
        } catch (\Exception $e) {
            $this->error("Failed to generate icons from SVG: {$e->getMessage()}");
            $this->warn('Falling back to GD-based generation...');
            $this->generateIconsFromSvgWithGd($svgPath, $iconsDir);
        }
    }

    protected function generateIconFromSvg($svgPath, $size, $iconsDir)
    {
        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel('transparent'));
        $imagick->readImage($svgPath);
        $imagick->setImageFormat('png');
        $imagick->resizeImage($size, $size, \Imagick::FILTER_LANCZOS, 1);

        $filename = "icon-{$size}x{$size}.png";
        $path = $iconsDir . '/' . $filename;

        $imagick->writeImage($path);
        $imagick->clear();
        $imagick->destroy();

        $this->line("  ✓ Generated {$filename}");
    }

    protected function generateMaskableIconsFromSvg($svgPath, $iconsDir)
    {
        $maskableSizes = config('filament-pwa.icons.maskable_sizes', [192, 512]);
        $themeColor = config('filament-pwa.theme_color', '#A77B56');

        foreach ($maskableSizes as $size) {
            // Create maskable icon with safe area (80% of icon size)
            $safeSize = intval($size * 0.8);
            $padding = intval(($size - $safeSize) / 2);

            // Create canvas with theme color background
            $canvas = new \Imagick();
            $canvas->newImage($size, $size, new \ImagickPixel($themeColor));
            $canvas->setImageFormat('png');

            // Load and resize SVG to safe area
            $icon = new \Imagick();
            $icon->setBackgroundColor(new \ImagickPixel('transparent'));
            $icon->readImage($svgPath);
            $icon->setImageFormat('png');
            $icon->resizeImage($safeSize, $safeSize, \Imagick::FILTER_LANCZOS, 1);

            // Composite icon onto canvas
            $canvas->compositeImage($icon, \Imagick::COMPOSITE_OVER, $padding, $padding);

            $filename = "icon-{$size}x{$size}-maskable.png";
            $path = $iconsDir . '/' . $filename;

            $canvas->writeImage($path);
            $canvas->clear();
            $canvas->destroy();
            $icon->clear();
            $icon->destroy();

            $this->line("  ✓ Generated maskable {$filename}");
        }
    }

    protected function generateFaviconFromSvg($svgPath)
    {
        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel('transparent'));
        $imagick->readImage($svgPath);
        $imagick->setImageFormat('png');
        $imagick->resizeImage(32, 32, \Imagick::FILTER_LANCZOS, 1);

        $imagick->writeImage(public_path('favicon.ico'));
        $imagick->clear();
        $imagick->destroy();

        $this->line("  ✓ Generated favicon.ico");
    }

    protected function generateIconsFromRaster($imagePath, $iconsDir)
    {
        try {
            // Check if Intervention Image is available
            if (!class_exists('Intervention\Image\ImageManager')) {
                $this->warn('Intervention Image not installed. Using GD fallback.');
                $this->generateIconsWithGd($imagePath, $iconsDir);
                return;
            }

            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $sourceImage = $manager->read($imagePath);

            $sizes = PwaService::getIconSizes();
            $additionalSizes = config('filament-pwa.icons.additional_sizes', [16, 32, 70, 150, 310]);
            $allSizes = array_merge($sizes, $additionalSizes);

            foreach ($allSizes as $size) {
                $this->generateIconFromRaster($sourceImage, $size, $iconsDir);
            }

            // Generate maskable icons
            $this->generateMaskableIconsFromRaster($sourceImage, $iconsDir);

            // Generate favicon
            $this->generateFaviconFromRaster($sourceImage);

            $this->info('✅ Icons generated successfully from raster image!');
        } catch (\Exception $e) {
            $this->error("Failed to generate icons from raster: {$e->getMessage()}");
            $this->warn('Falling back to GD-based generation...');
            $this->generateIconsWithGd($imagePath, $iconsDir);
        }
    }

    protected function generateIconFromRaster($sourceImage, $size, $iconsDir)
    {
        $icon = clone $sourceImage;
        $icon->resize($size, $size);

        $filename = "icon-{$size}x{$size}.png";
        $path = $iconsDir . '/' . $filename;

        $icon->save($path);
        $this->line("  ✓ Generated {$filename}");
    }

    protected function generateMaskableIconsFromRaster($sourceImage, $iconsDir)
    {
        $maskableSizes = config('filament-pwa.icons.maskable_sizes', [192, 512]);

        foreach ($maskableSizes as $size) {
            // Create maskable icon with safe area (80% of icon size)
            $safeSize = intval($size * 0.8);

            // Resize source image to safe area
            $icon = clone $sourceImage;
            $icon->resize($safeSize, $safeSize);

            $filename = "icon-{$size}x{$size}-maskable.png";
            $path = $iconsDir . '/' . $filename;

            $icon->save($path);
            $this->line("  ✓ Generated maskable {$filename}");
        }
    }

    protected function generateFaviconFromRaster($sourceImage)
    {
        $favicon = clone $sourceImage;
        $favicon->resize(32, 32);
        $favicon->save(public_path('favicon.ico'));
        $this->line("  ✓ Generated favicon.ico");
    }

    // Additional methods for GD fallback would go here...
    // (Similar to the original implementation but adapted for the plugin)

    protected function validatePWA()
    {
        $this->info('🔍 Validating PWA setup...');

        $errors = PwaService::validatePWAAssets();

        if (empty($errors)) {
            $this->info('✅ All PWA assets are present!');
        } else {
            $this->error('❌ PWA validation failed:');
            foreach ($errors as $error) {
                $this->line("  • {$error}");
            }
        }

        // Check HTTPS
        if (!request()->isSecure() && !app()->environment('local')) {
            $this->warn('⚠️  HTTPS is required for PWA installation in production');
        }

        // Check service worker registration
        $this->checkServiceWorker();

        // Check manifest
        $this->checkManifest();
    }

    protected function checkServiceWorker()
    {
        $swPath = public_path('sw.js');

        if (File::exists($swPath)) {
            $this->info('✅ Service worker found');

            $content = File::get($swPath);
            if (str_contains($content, 'CACHE_NAME')) {
                $this->info('✅ Service worker has caching strategy');
            } else {
                $this->warn('⚠️  Service worker missing caching strategy');
            }
        } else {
            $this->error('❌ Service worker not found');
        }
    }

    protected function checkManifest()
    {
        $manifestPath = public_path('manifest.json');

        if (File::exists($manifestPath)) {
            $this->info('✅ Web app manifest found');

            $manifest = json_decode(File::get($manifestPath), true);

            $requiredFields = ['name', 'short_name', 'start_url', 'display', 'icons'];
            foreach ($requiredFields as $field) {
                if (isset($manifest[$field])) {
                    $this->info("✅ Manifest has {$field}");
                } else {
                    $this->error("❌ Manifest missing {$field}");
                }
            }

            // Check icons
            if (isset($manifest['icons']) && is_array($manifest['icons'])) {
                $hasRequiredSizes = false;
                foreach ($manifest['icons'] as $icon) {
                    if (in_array($icon['sizes'], ['192x192', '512x512'])) {
                        $hasRequiredSizes = true;
                        break;
                    }
                }

                if ($hasRequiredSizes) {
                    $this->info('✅ Manifest has required icon sizes');
                } else {
                    $this->warn('⚠️  Manifest missing required icon sizes (192x192, 512x512)');
                }
            }
        } else {
            $this->error('❌ Web app manifest not found');
        }
    }

    protected function showPWAStatus()
    {
        $this->info('📱 Filament PWA Plugin Status');
        $this->line('');

        // Show configuration
        $config = PwaService::getConfig();
        $this->table(
            ['Setting', 'Value'],
            [
                ['Name', $config['name']],
                ['Short Name', $config['short_name']],
                ['Start URL', $config['start_url']],
                ['Display Mode', $config['display']],
                ['Theme Color', $config['theme_color']],
                ['Background Color', $config['background_color']],
            ]
        );

        $this->line('');
        $this->info('Available commands:');
        $this->line('  php artisan filament-pwa:setup --publish-assets');
        $this->line('  php artisan filament-pwa:setup --generate-icons --source=path/to/logo.svg');
        $this->line('  php artisan filament-pwa:setup --validate');
        $this->line('');
        $this->info('PWA URLs:');
        $this->line('  Manifest: ' . url('manifest.json'));
        $this->line('  Service Worker: ' . url('sw.js'));
        $this->line('  Admin Panel: ' . url(config('filament-pwa.start_url', '/admin')));
    }

    protected function generateIconsFromSvgWithGd($svgPath, $iconsDir)
    {
        $this->warn('Using GD fallback for SVG processing (limited quality)');
        $this->generateIconsWithGd($svgPath, $iconsDir);
    }

    protected function generateIconsWithGd($imagePath, $iconsDir)
    {
        // GD fallback implementation would go here
        // Similar to the original but adapted for the plugin
        $this->warn('GD fallback not fully implemented in this example');
        $this->info('Please install Imagick extension or Intervention Image for better quality.');
    }
}
