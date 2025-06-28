<?php

namespace Alareqi\FilamentPwa\Tests\Unit;

use Alareqi\FilamentPwa\Services\PwaService;
use Alareqi\FilamentPwa\Tests\TestCase;

class PwaServiceTest extends TestCase
{
    /** @test */
    public function it_generates_proper_config()
    {
        $config = PwaService::getConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('name', $config);
        $this->assertArrayHasKey('short_name', $config);
        $this->assertArrayHasKey('start_url', $config);
        $this->assertArrayHasKey('display', $config);
        $this->assertArrayHasKey('icons', $config);
    }

    /** @test */
    public function it_merges_custom_config_with_defaults()
    {
        $customConfig = [
            'app_name' => 'Custom App',
            'theme_color' => '#FF0000',
        ];

        $config = PwaService::getConfig($customConfig);

        $this->assertEquals('Custom App', $config['name']);
        $this->assertEquals('#FF0000', $config['theme_color']);
        
        // Should still have default values for other fields
        $this->assertArrayHasKey('short_name', $config);
        $this->assertArrayHasKey('start_url', $config);
    }

    /** @test */
    public function it_generates_icon_urls()
    {
        $icons = PwaService::getIconUrls();

        $this->assertIsArray($icons);
        $this->assertNotEmpty($icons);

        foreach ($icons as $icon) {
            $this->assertArrayHasKey('src', $icon);
            $this->assertArrayHasKey('sizes', $icon);
            $this->assertArrayHasKey('type', $icon);
            $this->assertArrayHasKey('purpose', $icon);
        }
    }

    /** @test */
    public function it_returns_proper_icon_sizes()
    {
        $sizes = PwaService::getIconSizes();

        $this->assertIsArray($sizes);
        $this->assertContains(192, $sizes);
        $this->assertContains(512, $sizes);
    }

    /** @test */
    public function it_generates_shortcuts()
    {
        $shortcuts = PwaService::getShortcuts();

        $this->assertIsArray($shortcuts);
        
        if (!empty($shortcuts)) {
            foreach ($shortcuts as $shortcut) {
                $this->assertArrayHasKey('name', $shortcut);
                $this->assertArrayHasKey('url', $shortcut);
            }
        }
    }

    /** @test */
    public function it_validates_pwa_assets()
    {
        $errors = PwaService::validatePWAAssets();

        $this->assertIsArray($errors);
        // In test environment, assets might not exist, so we just check the method works
    }

    /** @test */
    public function it_generates_installation_prompt_data()
    {
        $data = PwaService::getInstallationPromptData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('install_button', $data);
        $this->assertArrayHasKey('cancel_button', $data);
        $this->assertArrayHasKey('features', $data);
        $this->assertArrayHasKey('enabled', $data);
    }

    /** @test */
    public function it_generates_service_worker_config()
    {
        $config = PwaService::getServiceWorkerConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('cache_name', $config);
        $this->assertArrayHasKey('offline_url', $config);
        $this->assertArrayHasKey('cache_urls', $config);
        $this->assertArrayHasKey('cache_patterns', $config);
    }

    /** @test */
    public function it_detects_pwa_requests()
    {
        // Test default case (not a PWA request)
        $this->assertFalse(PwaService::isPWARequest());

        // Test with PWA header
        request()->headers->set('X-Requested-With', 'PWA');
        $this->assertTrue(PwaService::isPWARequest());
    }

    /** @test */
    public function it_generates_meta_tags()
    {
        $metaTags = PwaService::getMetaTags();

        $this->assertIsString($metaTags);
        $this->assertStringContainsString('<meta', $metaTags);
        $this->assertStringContainsString('manifest', $metaTags);
        $this->assertStringContainsString('theme-color', $metaTags);
    }

    /** @test */
    public function it_generates_installation_script()
    {
        $script = PwaService::getInstallationScript();

        $this->assertIsString($script);
        $this->assertStringContainsString('<script', $script);
        $this->assertStringContainsString('PWAInstaller', $script);
        $this->assertStringContainsString('serviceWorker', $script);
    }

    /** @test */
    public function it_respects_configuration_overrides()
    {
        config([
            'filament-pwa.app_name' => 'Test App Override',
            'filament-pwa.theme_color' => '#00FF00',
        ]);

        $config = PwaService::getConfig();

        $this->assertEquals('Test App Override', $config['name']);
        $this->assertEquals('#00FF00', $config['theme_color']);
    }

    /** @test */
    public function it_handles_rtl_configuration()
    {
        $config = PwaService::getConfig(['dir' => 'rtl', 'lang' => 'ar']);

        $this->assertEquals('rtl', $config['dir']);
        $this->assertEquals('ar', $config['lang']);
    }

    /** @test */
    public function it_generates_proper_icon_purposes()
    {
        $icons = PwaService::getIconUrls();

        $purposes = array_column($icons, 'purpose');
        
        $this->assertContains('any', $purposes);
        $this->assertContains('maskable', $purposes);
    }

    /** @test */
    public function service_worker_config_includes_cache_patterns()
    {
        $config = PwaService::getServiceWorkerConfig();

        $this->assertArrayHasKey('cache_patterns', $config);
        $this->assertIsArray($config['cache_patterns']);
        
        // Check for common cache patterns
        $patterns = $config['cache_patterns'];
        $this->assertArrayHasKey('filament_assets', $patterns);
        $this->assertArrayHasKey('images', $patterns);
        $this->assertArrayHasKey('fonts', $patterns);
    }
}
