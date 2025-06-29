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
        $config = self::getConfig($config);

        return View::make('filament-pwa::meta-tags', compact('config'))->render();
    }

    /**
     * Get PWA installation script
     */
    public static function getInstallationScript(array $config = []): string
    {
        $config = self::getConfig($config);

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

        // Extract current panel if provided
        $currentPanel = $config['_current_panel'] ?? null;
        unset($config['_current_panel']); // Remove from final config

        return [
            'name' => self::evaluateConfigValue($config['name'] ?? $config['app_name'] ?? $config['name']), // Support both old and new keys
            'short_name' => self::evaluateConfigValue($config['short_name']),
            'description' => self::evaluateConfigValue($config['description']),
            'start_url' => self::evaluateConfigValue($config['start_url']),
            'display' => self::evaluateConfigValue($config['display']),
            'background_color' => self::evaluateConfigValue($config['background_color']),
            'theme_color' => self::evaluateConfigValue($config['theme_color'] ?? self::getDefaultThemeColor($currentPanel)),
            'orientation' => self::evaluateConfigValue($config['orientation']),
            'scope' => self::evaluateConfigValue($config['scope']),
            'lang' => self::evaluateConfigValue($config['lang'] ?? self::getDefaultLanguage()),
            'dir' => self::evaluateConfigValue($config['dir'] ?? self::getDefaultDirection()),
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
            'theme_color' => self::getDefaultThemeColor(),
            'orientation' => 'portrait-primary',
            'scope' => '/admin',
            'lang' => self::getDefaultLanguage(),
            'dir' => self::getDefaultDirection(),
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
     * Get default theme color from Filament or fallback
     */
    protected static function getDefaultThemeColor($currentPanel = null): string
    {
        try {
            // Method 1: Try to get from provided panel context (highest priority)
            if ($currentPanel) {
                $primaryColor = self::extractColorFromPanel($currentPanel);
                if ($primaryColor) {
                    return $primaryColor;
                }
            }

            // Method 2: Try to get from current panel context (runtime detection)
            $detectedPanel = self::getCurrentPanel();
            if ($detectedPanel) {
                $primaryColor = self::extractColorFromPanel($detectedPanel);
                if ($primaryColor) {
                    return $primaryColor;
                }
            }

            // Method 3: Try to get from Filament Facades (all panels)
            if (class_exists(\Filament\Facades\Filament::class)) {
                $panels = \Filament\Facades\Filament::getPanels();

                // Try to find admin panel first, then any panel
                $targetPanel = $panels['admin'] ?? collect($panels)->first();

                if ($targetPanel) {
                    $primaryColor = self::extractColorFromPanel($targetPanel);
                    if ($primaryColor) {
                        return $primaryColor;
                    }
                }
            }

            // Method 4: Try to get from PanelManager directly
            if (class_exists(\Filament\PanelManager::class)) {
                $panelManager = app(\Filament\PanelManager::class);
                $panels = $panelManager->getPanels();

                $targetPanel = $panels['admin'] ?? collect($panels)->first();

                if ($targetPanel) {
                    $primaryColor = self::extractColorFromPanel($targetPanel);
                    if ($primaryColor) {
                        return $primaryColor;
                    }
                }
            }

            // Method 5: Try to get from Filament config files
            $configPaths = [
                'filament.theme.colors.primary',
                'filament.colors.primary',
                'filament.default.colors.primary',
            ];

            foreach ($configPaths as $configPath) {
                $filamentConfig = config($configPath);
                if ($filamentConfig) {
                    $primaryColor = self::extractPrimaryColor(['primary' => $filamentConfig]);
                    if ($primaryColor) {
                        return $primaryColor;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log the error for debugging but continue with fallback
            if (config('app.debug')) {
                \Log::warning('PWA color detection failed: ' . $e->getMessage());
            }
        }

        return '#6366f1'; // Tailwind Indigo 500 (Filament's default)
    }

    /**
     * Get the current active Filament panel
     */
    protected static function getCurrentPanel()
    {
        try {
            // Method 1: Try to get current panel from Filament facade
            if (class_exists(\Filament\Facades\Filament::class)) {
                $currentPanel = \Filament\Facades\Filament::getCurrentPanel();
                if ($currentPanel) {
                    return $currentPanel;
                }
            }

            // Method 2: Try to detect from current route
            $currentRoute = request()->route();
            if ($currentRoute) {
                $routeName = $currentRoute->getName();
                if ($routeName && str_contains($routeName, 'filament.')) {
                    // Extract panel ID from route name (e.g., 'filament.admin.pages.dashboard')
                    $parts = explode('.', $routeName);
                    if (count($parts) >= 2 && $parts[0] === 'filament') {
                        $panelId = $parts[1];

                        if (class_exists(\Filament\Facades\Filament::class)) {
                            $panels = \Filament\Facades\Filament::getPanels();
                            if (isset($panels[$panelId])) {
                                return $panels[$panelId];
                            }
                        }
                    }
                }
            }

            // Method 3: Try to get from request path
            $path = request()->path();
            if (str_starts_with($path, 'admin')) {
                if (class_exists(\Filament\Facades\Filament::class)) {
                    $panels = \Filament\Facades\Filament::getPanels();
                    if (isset($panels['admin'])) {
                        return $panels['admin'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Continue to fallback methods
        }

    }

    /**
     * Extract color from a Filament panel instance
     */
    protected static function extractColorFromPanel($panel): ?string
    {
        try {
            if (! $panel || ! method_exists($panel, 'getColors')) {
                return null;
            }

            $colors = $panel->getColors();

            return self::extractPrimaryColor($colors);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract primary color from Filament colors configuration
     */
    protected static function extractPrimaryColor(array $colors): ?string
    {
        if (! isset($colors['primary'])) {
            return null;
        }

        $primary = $colors['primary'];

        // Handle Filament v4 Color constants (arrays with OKLCH values)
        if (is_array($primary)) {
            return self::extractFromColorArray($primary);
        }

        // Handle Filament Color objects (v3 compatibility)
        if (is_object($primary)) {
            // Try to get the color value from Filament Color object
            if (method_exists($primary, 'getColor')) {
                $colorValue = $primary->getColor();
                if (is_string($colorValue)) {
                    return self::normalizeColorValue($colorValue);
                }
            }

            // Try to get array representation
            if (method_exists($primary, 'toArray')) {
                $colorArray = $primary->toArray();
                if (is_array($colorArray)) {
                    return self::extractFromColorArray($colorArray);
                }
            }

            // Try to convert to string
            if (method_exists($primary, '__toString')) {
                $colorString = (string) $primary;

                return self::normalizeColorValue($colorString);
            }
        }

        // Handle string colors (hex, rgb, etc.)
        if (is_string($primary)) {
            return self::normalizeColorValue($primary);
        }

        return null;
    }

    /**
     * Extract color from color array (shade-based)
     */
    protected static function extractFromColorArray(array $colorArray): ?string
    {
        // Try different shades in order of preference
        $preferredShades = [600, 500, 700, 400, 800, 300, 900, 200, 100, 50];

        foreach ($preferredShades as $shade) {
            if (isset($colorArray[$shade])) {
                $colorValue = $colorArray[$shade];
                if (is_string($colorValue)) {
                    // Handle Filament v4 OKLCH format
                    if (str_starts_with($colorValue, 'oklch(')) {
                        try {
                            // Use Filament's built-in color conversion
                            if (class_exists(\Filament\Support\Colors\Color::class)) {
                                $rgb = \Filament\Support\Colors\Color::convertToRgb($colorValue);
                                $hex = self::convertRgbToHex($rgb);
                                if ($hex) {
                                    return $hex;
                                }
                            }
                        } catch (\Exception $e) {
                            // Fall back to normalization
                        }
                    }

                    // Try standard color normalization
                    $normalized = self::normalizeColorValue($colorValue);
                    if ($normalized) {
                        return $normalized;
                    }
                }
            }
        }

        // If no shade-based color found, try direct color values
        foreach ($colorArray as $key => $value) {
            if (is_string($value)) {
                // Handle OKLCH format for direct values too
                if (str_starts_with($value, 'oklch(')) {
                    try {
                        if (class_exists(\Filament\Support\Colors\Color::class)) {
                            $rgb = \Filament\Support\Colors\Color::convertToRgb($value);
                            $hex = self::convertRgbToHex($rgb);
                            if ($hex) {
                                return $hex;
                            }
                        }
                    } catch (\Exception $e) {
                        // Continue to normalization
                    }
                }

                $normalized = self::normalizeColorValue($value);
                if ($normalized) {
                    return $normalized;
                }
            }
        }

        return null;
    }

    /**
     * Normalize color value to hex format
     */
    protected static function normalizeColorValue(string $color): ?string
    {
        $color = trim($color);

        // If it's already a hex color, return it
        if (preg_match('/^#[0-9a-f]{6}$/i', $color)) {
            return $color;
        }

        // If it's hex without #, add it
        if (preg_match('/^[0-9a-f]{6}$/i', $color)) {
            return '#' . $color;
        }

        // Handle "r, g, b" format
        if (strpos($color, ',') !== false) {
            $rgbValues = array_map('trim', explode(',', $color));
            if (count($rgbValues) === 3) {
                $r = (int) $rgbValues[0];
                $g = (int) $rgbValues[1];
                $b = (int) $rgbValues[2];

                // Validate RGB values
                if ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255) {
                    return sprintf('#%02x%02x%02x', $r, $g, $b);
                }
            }
        }

        // Handle rgb() format
        if (preg_match('/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i', $color, $matches)) {
            $r = (int) $matches[1];
            $g = (int) $matches[2];
            $b = (int) $matches[3];

            if ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255) {
                return sprintf('#%02x%02x%02x', $r, $g, $b);
            }
        }

        // For other formats, return as-is if it looks like a valid color
        if (preg_match('/^#[0-9a-f]{3}$/i', $color)) {
            // Convert 3-digit hex to 6-digit
            $r = $color[1] . $color[1];
            $g = $color[2] . $color[2];
            $b = $color[3] . $color[3];

            return '#' . $r . $g . $b;
        }

        return null;
    }

    /**
     * Convert RGB string to hex format
     */
    protected static function convertRgbToHex(string $rgb): ?string
    {
        // Handle rgb(r, g, b) format
        if (preg_match('/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i', $rgb, $matches)) {
            $r = (int) $matches[1];
            $g = (int) $matches[2];
            $b = (int) $matches[3];

            // Validate RGB values
            if ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255) {
                return sprintf('#%02x%02x%02x', $r, $g, $b);
            }
        }

        return null;
    }

    /**
     * Debug method to check color detection
     * This method helps troubleshoot color detection issues
     */
    public static function debugColorDetection(): array
    {
        $debug = [
            'config_file_theme_color' => config('filament-pwa.theme_color'),
            'env_theme_color' => env('PWA_THEME_COLOR'),
            'detected_theme_color' => null,
            'filament_available' => false,
            'current_panel' => null,
            'current_panel_colors' => null,
            'panels_found' => [],
            'colors_found' => [],
            'color_extraction_steps' => [],
            'final_config' => null,
        ];

        try {
            // Check current panel
            $currentPanel = self::getCurrentPanel();
            if ($currentPanel) {
                $debug['current_panel'] = [
                    'id' => method_exists($currentPanel, 'getId') ? $currentPanel->getId() : 'unknown',
                    'class' => get_class($currentPanel),
                ];

                if (method_exists($currentPanel, 'getColors')) {
                    $debug['current_panel_colors'] = $currentPanel->getColors();
                    $extractedColor = self::extractColorFromPanel($currentPanel);
                    $debug['color_extraction_steps'][] = [
                        'method' => 'current_panel',
                        'result' => $extractedColor,
                        'raw_colors' => $currentPanel->getColors(),
                    ];
                }
            }

            // Check if Filament is available
            if (class_exists(\Filament\Facades\Filament::class)) {
                $debug['filament_available'] = true;
                $panels = \Filament\Facades\Filament::getPanels();
                $debug['panels_found'] = array_keys($panels);

                foreach ($panels as $panelId => $panel) {
                    if (method_exists($panel, 'getColors')) {
                        $colors = $panel->getColors();
                        $debug['colors_found'][$panelId] = $colors;

                        $extractedColor = self::extractColorFromPanel($panel);
                        $debug['color_extraction_steps'][] = [
                            'method' => "panel_{$panelId}",
                            'result' => $extractedColor,
                            'raw_colors' => $colors,
                        ];
                    }
                }
            }

            // Test config paths
            $configPaths = [
                'filament.theme.colors.primary',
                'filament.colors.primary',
                'filament.default.colors.primary',
            ];

            foreach ($configPaths as $configPath) {
                $configValue = config($configPath);
                $debug['color_extraction_steps'][] = [
                    'method' => "config_{$configPath}",
                    'result' => $configValue ? self::extractPrimaryColor(['primary' => $configValue]) : null,
                    'raw_value' => $configValue,
                ];
            }

            $debug['detected_theme_color'] = self::getDefaultThemeColor();
            $debug['final_config'] = self::getConfig();
        } catch (\Exception $e) {
            $debug['error'] = $e->getMessage();
            $debug['trace'] = $e->getTraceAsString();
        }

        return $debug;
    }

    /**
     * Get default language from Laravel app locale
     */
    protected static function getDefaultLanguage(): string
    {
        try {
            return app()->getLocale();
        } catch (\Exception $e) {
            return 'en';
        }
    }

    /**
     * Get default text direction based on language
     */
    protected static function getDefaultDirection(): string
    {
        try {
            $locale = app()->getLocale();
            $rtlLocales = ['ar', 'he', 'fa', 'ur', 'ku', 'dv', 'ps', 'sd', 'yi'];

            return in_array($locale, $rtlLocales) ? 'rtl' : 'ltr';
        } catch (\Exception $e) {
            return 'ltr';
        }
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

        // Check if manifest route is registered
        $manifestRouteExists = false;
        $swRouteExists = false;

        try {
            $routes = app('router')->getRoutes();
            foreach ($routes as $route) {
                if ($route->uri() === 'manifest.json') {
                    $manifestRouteExists = true;
                }
                if ($route->uri() === 'sw.js') {
                    $swRouteExists = true;
                }
            }
        } catch (\Exception $e) {
            // Fallback: check if routes are accessible via URL generation
            try {
                route('filament-pwa.manifest');
                $manifestRouteExists = true;
            } catch (\Exception $e) {
                // Route doesn't exist
            }

            try {
                route('filament-pwa.service-worker');
                $swRouteExists = true;
            } catch (\Exception $e) {
                // Route doesn't exist
            }
        }

        if (! $manifestRouteExists) {
            $errors[] = 'Manifest file not found';
        }

        if (! $swRouteExists) {
            $errors[] = 'Service worker not found';
        }

        // Check required icons (these should be physical files)
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
        $config = self::getConfig($config);
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
