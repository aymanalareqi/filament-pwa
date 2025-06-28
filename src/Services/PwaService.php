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
            'name' => self::evaluateConfigValue($config['name'] ?? $config['app_name'] ?? $config['name']), // Support both old and new keys
            'short_name' => self::evaluateConfigValue($config['short_name']),
            'description' => self::evaluateConfigValue($config['description']),
            'start_url' => self::evaluateConfigValue($config['start_url']),
            'display' => self::evaluateConfigValue($config['display']),
            'background_color' => self::evaluateConfigValue($config['background_color']),
            'theme_color' => self::evaluateConfigValue($config['theme_color']),
            'orientation' => self::evaluateConfigValue($config['orientation']),
            'scope' => self::evaluateConfigValue($config['scope']),
            'lang' => self::evaluateConfigValue($config['lang']),
            'dir' => self::evaluateConfigValue($config['dir']),
            'categories' => self::evaluateConfigValue($config['categories']),
            'prefer_related_applications' => self::evaluateConfigValue($config['prefer_related_applications'] ?? false),
            'icons' => self::getIconUrls($config),
            'shortcuts' => self::evaluateShortcuts($config['shortcuts'] ?? []),
            'screenshots' => self::evaluateConfigValue($config['screenshots'] ?? []),
            'related_applications' => self::evaluateConfigValue($config['related_applications'] ?? []),
            'installation_prompts' => self::evaluateConfigValue($config['installation'] ?? $config['installation_prompts'] ?? []), // Support both old and new keys
            'installation' => self::evaluateConfigValue($config['installation'] ?? $config['installation_prompts'] ?? []), // New installation config structure
        ];
    }

    /**
     * Evaluate a configuration value, executing closures if present
     *
     * @param  mixed  $value  The configuration value (could be a closure)
     * @return mixed The evaluated value
     */
    protected static function evaluateConfigValue(mixed $value): mixed
    {
        if ($value instanceof \Closure) {
            return $value();
        }

        return $value;
    }

    /**
     * Evaluate shortcuts array, handling closures for dynamic shortcuts
     *
     * @param  array  $shortcuts  The shortcuts configuration
     * @return array The evaluated shortcuts
     */
    protected static function evaluateShortcuts(array $shortcuts): array
    {
        $evaluatedShortcuts = [];

        foreach ($shortcuts as $shortcut) {
            if ($shortcut instanceof \Closure) {
                $evaluatedShortcut = $shortcut();
                if (is_array($evaluatedShortcut)) {
                    $evaluatedShortcuts[] = $evaluatedShortcut;
                }
            } else {
                // Evaluate individual shortcut properties that might be closures
                $evaluatedShortcuts[] = [
                    'name' => self::evaluateConfigValue($shortcut['name'] ?? ''),
                    'short_name' => self::evaluateConfigValue($shortcut['short_name'] ?? $shortcut['name'] ?? ''),
                    'description' => self::evaluateConfigValue($shortcut['description'] ?? $shortcut['name'] ?? ''),
                    'url' => self::evaluateConfigValue($shortcut['url'] ?? ''),
                    'icons' => self::evaluateConfigValue($shortcut['icons'] ?? []),
                ];
            }
        }

        return $evaluatedShortcuts;
    }

    /**
     * Get default configuration
     */
    protected static function getDefaultConfig(): array
    {
        return [
            'name' => config('app.name', 'Laravel') . ' Admin',
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
            'installation' => [
                'enabled' => true,
                'prompt_delay' => 2000,
                'ios_instructions_delay' => 5000,
                'show_banner_in_debug' => true,
            ],
            'icons' => [
                'path' => 'images/icons',
                'sizes' => [72, 96, 128, 144, 152, 192, 384, 512],
                'maskable_sizes' => [192, 512],
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
        $iconConfig = $config['icons'] ?? [];
        $outputPath = $iconConfig['path'] ?? $iconConfig['output_path'] ?? 'images/icons'; // Support both old and new keys

        $sizes = $iconConfig['sizes'] ?? self::getIconSizes();
        foreach ($sizes as $size) {
            $icons[] = [
                'src' => "/{$outputPath}/icon-{$size}x{$size}.png",
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'any',
            ];
        }

        // Add maskable icons
        $maskableSizes = $iconConfig['maskable_sizes'] ?? [192, 512];
        foreach ($maskableSizes as $size) {
            $icons[] = [
                'src' => "/{$outputPath}/icon-{$size}x{$size}-maskable.png",
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'maskable',
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
                        'sizes' => '96x96',
                    ],
                ],
            ],
        ];
    }

    /**
     * Check if all required PWA assets exist
     */
    public static function validatePWAAssets(): array
    {
        $errors = [];

        // Check manifest file
        if (! file_exists(public_path('manifest.json'))) {
            $errors[] = 'Manifest file not found';
        }

        // Check service worker
        if (! file_exists(public_path('sw.js'))) {
            $errors[] = 'Service worker not found';
        }

        // Check required icons
        $requiredIcons = [192, 512];
        foreach ($requiredIcons as $size) {
            $iconPath = public_path("images/icons/icon-{$size}x{$size}.png");
            if (! file_exists($iconPath)) {
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
        $installationConfig = $config['installation'] ?? $config['installation_prompts'] ?? []; // Support both old and new keys

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
                'Improved performance',
            ],
            'enabled' => $installationConfig['enabled'] ?? true,
            'delay' => $installationConfig['prompt_delay'] ?? $installationConfig['delay'] ?? 2000, // Support both old and new keys
            'ios_instructions_delay' => $installationConfig['ios_instructions_delay'] ?? 5000,
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
