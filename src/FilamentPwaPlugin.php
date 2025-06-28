<?php

namespace Alareqi\FilamentPwa;

use Alareqi\FilamentPwa\Services\PwaService;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;

class FilamentPwaPlugin implements Plugin
{
    protected array $config = [];

    public function getId(): string
    {
        return 'filament-pwa';
    }

    public function register(Panel $panel): void
    {
        // Merge plugin config with global config
        $mergedConfig = array_merge(config('filament-pwa', []), $this->config);

        // Register PWA meta tags in the head
        $panel->renderHook(
            PanelsRenderHook::HEAD_START,
            fn(): string => PwaService::getMetaTags($mergedConfig)
        );

        // Register PWA installation script at the end of body
        $panel->renderHook(
            PanelsRenderHook::BODY_END,
            fn(): string => PwaService::getInstallationScript($mergedConfig)
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * Configure the PWA app name
     *
     * @param  string|\Closure  $name  The app name or a closure that returns the app name
     */
    public function name(string|\Closure $name): static
    {
        $this->config['name'] = $name;

        return $this;
    }

    /**
     * Configure the PWA app name (alias for name method)
     *
     * @param  string|\Closure  $name  The app name or a closure that returns the app name
     */
    public function appName(string|\Closure $name): static
    {
        return $this->name($name);
    }

    /**
     * Configure the PWA short name
     *
     * @param  string|\Closure  $shortName  The short name or a closure that returns the short name
     */
    public function shortName(string|\Closure $shortName): static
    {
        $this->config['short_name'] = $shortName;

        return $this;
    }

    /**
     * Configure the PWA description
     *
     * @param  string|\Closure  $description  The description or a closure that returns the description
     */
    public function description(string|\Closure $description): static
    {
        $this->config['description'] = $description;

        return $this;
    }

    /**
     * Configure the PWA start URL
     *
     * @param  string|\Closure  $startUrl  The start URL or a closure that returns the start URL
     */
    public function startUrl(string|\Closure $startUrl): static
    {
        $this->config['start_url'] = $startUrl;

        return $this;
    }

    /**
     * Configure the PWA theme color
     *
     * @param  string|\Closure  $themeColor  The theme color or a closure that returns the theme color
     */
    public function themeColor(string|\Closure $themeColor): static
    {
        $this->config['theme_color'] = $themeColor;

        return $this;
    }

    /**
     * Configure the PWA background color
     *
     * @param  string|\Closure  $backgroundColor  The background color or a closure that returns the background color
     */
    public function backgroundColor(string|\Closure $backgroundColor): static
    {
        $this->config['background_color'] = $backgroundColor;

        return $this;
    }

    /**
     * Configure the PWA display mode
     * Available modes: standalone, fullscreen, minimal-ui, browser
     */
    public function displayMode(string $displayMode): static
    {
        $this->config['display'] = $displayMode;

        return $this;
    }

    /**
     * Set display mode to standalone (recommended for PWAs)
     */
    public function standalone(): static
    {
        return $this->displayMode('standalone');
    }

    /**
     * Set display mode to fullscreen
     */
    public function fullscreen(): static
    {
        return $this->displayMode('fullscreen');
    }

    /**
     * Configure the PWA orientation
     * Available orientations: portrait, landscape, portrait-primary, landscape-primary, any
     */
    public function orientation(string $orientation): static
    {
        $this->config['orientation'] = $orientation;

        return $this;
    }

    /**
     * Set orientation to portrait
     */
    public function portrait(): static
    {
        return $this->orientation('portrait');
    }

    /**
     * Set orientation to landscape
     */
    public function landscape(): static
    {
        return $this->orientation('landscape');
    }

    /**
     * Configure the PWA scope
     */
    public function scope(string $scope): static
    {
        $this->config['scope'] = $scope;

        return $this;
    }

    /**
     * Configure the PWA language
     *
     * @param  string|\Closure  $language  The language code or a closure that returns the language code
     */
    public function language(string|\Closure $language): static
    {
        $this->config['lang'] = $language;

        return $this;
    }

    /**
     * Configure the PWA text direction
     * Available directions: ltr, rtl
     *
     * @param  string|\Closure  $direction  The text direction or a closure that returns the text direction
     */
    public function direction(string|\Closure $direction): static
    {
        $this->config['dir'] = $direction;

        return $this;
    }

    /**
     * Set text direction to left-to-right
     */
    public function ltr(): static
    {
        return $this->direction('ltr');
    }

    /**
     * Set text direction to right-to-left
     */
    public function rtl(): static
    {
        return $this->direction('rtl');
    }

    /**
     * Configure PWA categories
     */
    public function categories(array $categories): static
    {
        $this->config['categories'] = $categories;

        return $this;
    }

    /**
     * Configure PWA shortcuts
     */
    public function shortcuts(array $shortcuts): static
    {
        $this->config['shortcuts'] = $shortcuts;

        return $this;
    }

    /**
     * Configure installation prompts
     */
    public function installation(bool $enabled = true, int $promptDelay = 2000, int $iosInstructionsDelay = 5000, ?bool $showBannerInDebug = null): static
    {
        $this->config['installation'] = [
            'enabled' => $enabled,
            'prompt_delay' => $promptDelay,
            'ios_instructions_delay' => $iosInstructionsDelay,
            'show_banner_in_debug' => $showBannerInDebug ?? true,
        ];

        return $this;
    }

    /**
     * Enable installation prompts
     */
    public function enableInstallation(int $promptDelay = 2000): static
    {
        return $this->installation(true, $promptDelay);
    }

    /**
     * Disable installation prompts
     */
    public function disableInstallation(): static
    {
        return $this->installation(false);
    }

    /**
     * Enable debug mode for installation banner
     * This will always show the installation banner in debug mode,
     * bypassing dismissal logic and browser installation state checks
     */
    public function enableDebugBanner(bool $enabled = true): static
    {
        if (!isset($this->config['installation'])) {
            $this->config['installation'] = [];
        }

        $this->config['installation']['show_banner_in_debug'] = $enabled;

        return $this;
    }

    /**
     * Disable debug mode for installation banner
     */
    public function disableDebugBanner(): static
    {
        return $this->enableDebugBanner(false);
    }

    /**
     * Configure icon settings
     */
    public function icons(string $path = 'images/icons', ?array $sizes = null, ?array $maskableSizes = null): static
    {
        $this->config['icons'] = [
            'path' => $path,
            'sizes' => $sizes ?? [72, 96, 128, 144, 152, 192, 384, 512],
            'maskable_sizes' => $maskableSizes ?? [192, 512],
        ];

        return $this;
    }

    /**
     * Configure service worker settings
     */
    public function serviceWorker(?string $cacheName = null, string $offlineUrl = '/offline', ?array $cacheUrls = null): static
    {
        $this->config['service_worker'] = [
            'cache_name' => $cacheName ?? 'filament-admin-v1.0.0',
            'offline_url' => $offlineUrl,
            'cache_urls' => $cacheUrls ?? ['/admin', '/admin/login', '/manifest.json'],
            'cache_patterns' => [
                'filament_assets' => '/\/css\/filament\/|\/js\/filament\//',
                'images' => '/\.(png|jpg|jpeg|svg|gif|webp|ico)$/',
                'fonts' => '/\.(woff|woff2|ttf|eot)$/',
            ],
        ];

        return $this;
    }

    /**
     * Add a shortcut to the PWA
     *
     * @param  string|\Closure  $name  The shortcut name or a closure that returns shortcut data
     * @param  string|\Closure|null  $url  The URL or a closure that returns the URL (ignored if $name is a closure)
     * @param  string|\Closure|null  $description  The description or a closure that returns the description (ignored if $name is a closure)
     * @param  array|\Closure  $icons  The icons array or a closure that returns the icons (ignored if $name is a closure)
     */
    public function addShortcut(string|\Closure $name, string|\Closure|null $url = null, string|\Closure|null $description = null, array|\Closure $icons = []): static
    {
        if (! isset($this->config['shortcuts'])) {
            $this->config['shortcuts'] = [];
        }

        // If name is a closure, it should return the complete shortcut data
        if ($name instanceof \Closure) {
            $this->config['shortcuts'][] = $name;
        } else {
            // Traditional approach with individual parameters
            $this->config['shortcuts'][] = [
                'name' => $name,
                'short_name' => $name,
                'description' => $description ?? $name,
                'url' => $url,
                'icons' => $icons ?: [
                    [
                        'src' => '/images/icons/icon-96x96.png',
                        'sizes' => '96x96',
                    ],
                ],
            ];
        }

        return $this;
    }

    /**
     * Get the current configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
