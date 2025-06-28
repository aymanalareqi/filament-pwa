<?php

namespace Alareqi\FilamentPwa\Services;

use Illuminate\Support\Facades\View;

class PwaService
{
    /**
     * Get PWA meta tags for the admin panel
     */
    public static function getMetaTags(array $config = []): string
    {
        $config = array_merge(self::getDefaultConfig(), $config);
        
        return View::make('filament-pwa::meta-tags', compact('config'))->render();
    }

    /**
     * Get PWA installation script
     */
    public static function getInstallationScript(array $config = []): string
    {
        $config = array_merge(self::getDefaultConfig(), $config);
        
        return View::make('filament-pwa::installation-script', compact('config'))->render();
    }

    /**
     * Check if the current request is from a PWA
     */
    public static function isPWARequest(): bool
    {
        return request()->header('X-Requested-With') === 'PWA' ||
               request()->query('pwa') === '1' ||
               request()->header('User-Agent', '') !== '' && 
               str_contains(request()->header('User-Agent'), 'PWA');
    }

    /**
     * Get PWA configuration array
     */
    public static function getConfig(array $overrides = []): array
    {
        $config = array_merge(self::getDefaultConfig(), config('filament-pwa', []), $overrides);
        
        return [
            'name' => $config['app_name'],
            'short_name' => $config['short_name'],
            'description' => $config['description'],
            'start_url' => $config['start_url'],
            'display' => $config['display'],
            'background_color' => $config['background_color'],
            'theme_color' => $config['theme_color'],
            'orientation' => $config['orientation'],
            'scope' => $config['scope'],
            'lang' => $config['lang'],
            'dir' => $config['dir'],
            'categories' => $config['categories'],
            'prefer_related_applications' => $config['prefer_related_applications'] ?? false,
            'icons' => self::getIconUrls($config),
            'shortcuts' => $config['shortcuts'] ?? [],
            'screenshots' => $config['screenshots'] ?? [],
            'related_applications' => $config['related_applications'] ?? [],
        ];
    }

    /**
     * Get default configuration
     */
    protected static function getDefaultConfig(): array
    {
        return [
            'app_name' => config('app.name', 'Laravel') . ' Admin',
            'short_name' => 'Admin',
            'description' => 'Admin panel for ' . config('app.name', 'Laravel'),
            'start_url' => '/admin',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#A77B56',
            'orientation' => 'portrait-primary',
            'scope' => '/admin',
            'lang' => 'en',
            'dir' => 'ltr',
            'categories' => ['productivity', 'business'],
            'prefer_related_applications' => false,
            'installation_prompts' => [
                'enabled' => true,
                'delay' => 2000,
                'ios_instructions_delay' => 5000,
            ],
        ];
    }

    /**
     * Get available icon sizes
     */
    public static function getIconSizes(): array
    {
        return config('filament-pwa.icons.sizes', [72, 96, 128, 144, 152, 192, 384, 512]);
    }

    /**
     * Generate icon URLs for all sizes
     */
    public static function getIconUrls(array $config = []): array
    {
        $icons = [];
        $outputPath = $config['icons']['output_path'] ?? 'images/icons';
        
        foreach (self::getIconSizes() as $size) {
            $icons[] = [
                'src' => "/{$outputPath}/icon-{$size}x{$size}.png",
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'any'
            ];
        }

        // Add maskable icons
        $maskableSizes = $config['icons']['maskable_sizes'] ?? [192, 512];
        foreach ($maskableSizes as $size) {
            $icons[] = [
                'src' => "/{$outputPath}/icon-{$size}x{$size}-maskable.png",
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'maskable'
            ];
        }

        return $icons;
    }

    /**
     * Get PWA shortcuts for the manifest
     */
    public static function getShortcuts(array $config = []): array
    {
        return $config['shortcuts'] ?? [
            [
                'name' => 'Dashboard',
                'short_name' => 'Dashboard',
                'description' => 'Go to the main dashboard',
                'url' => '/admin',
                'icons' => [
                    [
                        'src' => '/images/icons/icon-96x96.png',
                        'sizes' => '96x96'
                    ]
                ]
            ]
        ];
    }

    /**
     * Check if all required PWA assets exist
     */
    public static function validatePWAAssets(): array
    {
        $errors = [];
        
        // Check manifest file
        if (!file_exists(public_path('manifest.json'))) {
            $errors[] = 'Manifest file not found';
        }

        // Check service worker
        if (!file_exists(public_path('sw.js'))) {
            $errors[] = 'Service worker not found';
        }

        // Check required icons
        $requiredIcons = [192, 512];
        foreach ($requiredIcons as $size) {
            $iconPath = public_path("images/icons/icon-{$size}x{$size}.png");
            if (!file_exists($iconPath)) {
                $errors[] = "Required icon {$size}x{$size} not found";
            }
        }

        return $errors;
    }

    /**
     * Generate PWA installation prompt data
     */
    public static function getInstallationPromptData(array $config = []): array
    {
        $config = array_merge(self::getDefaultConfig(), $config);
        
        return [
            'title' => 'Install App',
            'message' => 'Install this app on your device for quick access and offline functionality.',
            'install_button' => 'Install Now',
            'cancel_button' => 'Not Now',
            'features' => [
                'Quick access from home screen',
                'Work offline',
                'Native app experience',
                'Push notifications',
                'Improved performance'
            ],
            'enabled' => $config['installation_prompts']['enabled'] ?? true,
            'delay' => $config['installation_prompts']['delay'] ?? 2000,
            'ios_instructions_delay' => $config['installation_prompts']['ios_instructions_delay'] ?? 5000,
        ];
    }

    /**
     * Generate service worker configuration
     */
    public static function getServiceWorkerConfig(array $config = []): array
    {
        $swConfig = $config['service_worker'] ?? config('filament-pwa.service_worker', []);
        
        return [
            'cache_name' => $swConfig['cache_name'] ?? 'filament-admin-v1.0.0',
            'offline_url' => $swConfig['offline_url'] ?? '/offline',
            'cache_urls' => $swConfig['cache_urls'] ?? ['/admin', '/admin/login', '/manifest.json'],
            'cache_patterns' => $swConfig['cache_patterns'] ?? [
                'filament_assets' => '/\/css\/filament\/|\/js\/filament\//',
                'images' => '/\.(png|jpg|jpeg|svg|gif|webp|ico)$/',
                'fonts' => '/\.(woff|woff2|ttf|eot)$/',
            ],
        ];
    }
}
