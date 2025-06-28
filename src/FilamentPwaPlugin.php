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
     */
    public function appName(string $name): static
    {
        $this->config['app_name'] = $name;

        return $this;
    }

    /**
     * Configure the PWA short name
     */
    public function shortName(string $shortName): static
    {
        $this->config['short_name'] = $shortName;

        return $this;
    }

    /**
     * Configure the PWA description
     */
    public function description(string $description): static
    {
        $this->config['description'] = $description;

        return $this;
    }

    /**
     * Configure the PWA start URL
     */
    public function startUrl(string $startUrl): static
    {
        $this->config['start_url'] = $startUrl;

        return $this;
    }

    /**
     * Configure the PWA theme color
     */
    public function themeColor(string $themeColor): static
    {
        $this->config['theme_color'] = $themeColor;

        return $this;
    }

    /**
     * Configure the PWA background color
     */
    public function backgroundColor(string $backgroundColor): static
    {
        $this->config['background_color'] = $backgroundColor;

        return $this;
    }

    /**
     * Configure the PWA display mode
     */
    public function displayMode(string $displayMode): static
    {
        $this->config['display'] = $displayMode;

        return $this;
    }

    /**
     * Configure the PWA orientation
     */
    public function orientation(string $orientation): static
    {
        $this->config['orientation'] = $orientation;

        return $this;
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
     */
    public function language(string $language): static
    {
        $this->config['lang'] = $language;

        return $this;
    }

    /**
     * Configure the PWA text direction
     */
    public function direction(string $direction): static
    {
        $this->config['dir'] = $direction;

        return $this;
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
     * Enable or disable installation prompts
     */
    public function installationPrompts(bool $enabled = true): static
    {
        $this->config['installation_prompts'] = $enabled;

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
