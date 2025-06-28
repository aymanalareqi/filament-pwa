<?php

namespace Alareqi\FilamentPwa\Tests\Feature;

use Alareqi\FilamentPwa\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PwaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_serves_manifest_json()
    {
        $response = $this->get('/manifest.json');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $manifest = $response->json();

        $this->assertArrayHasKey('name', $manifest);
        $this->assertArrayHasKey('short_name', $manifest);
        $this->assertArrayHasKey('start_url', $manifest);
        $this->assertArrayHasKey('display', $manifest);
        $this->assertArrayHasKey('icons', $manifest);
    }

    /** @test */
    public function it_serves_service_worker()
    {
        $response = $this->get('/sw.js');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/javascript');

        $content = $response->getContent();

        $this->assertStringContainsString('CACHE_NAME', $content);
        $this->assertStringContainsString('addEventListener', $content);
    }

    /** @test */
    public function it_serves_browser_config()
    {
        $response = $this->get('/browserconfig.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');

        $content = $response->getContent();

        $this->assertStringContainsString('<browserconfig>', $content);
        $this->assertStringContainsString('<msapplication>', $content);
    }

    /** @test */
    public function it_serves_offline_page()
    {
        $response = $this->get('/offline');

        $response->assertStatus(200);
        $response->assertViewIs('filament-pwa::offline');
    }

    /** @test */
    public function manifest_contains_required_fields()
    {
        $response = $this->get('/manifest.json');
        $manifest = $response->json();

        // Required PWA manifest fields
        $requiredFields = ['name', 'short_name', 'start_url', 'display', 'icons'];

        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $manifest, "Manifest missing required field: {$field}");
        }
    }

    /** @test */
    public function manifest_icons_have_required_sizes()
    {
        $response = $this->get('/manifest.json');
        $manifest = $response->json();

        $this->assertArrayHasKey('icons', $manifest);
        $this->assertIsArray($manifest['icons']);
        $this->assertNotEmpty($manifest['icons']);

        $iconSizes = array_column($manifest['icons'], 'sizes');

        // Check for required icon sizes
        $this->assertContains('192x192', $iconSizes, 'Manifest missing 192x192 icon');
        $this->assertContains('512x512', $iconSizes, 'Manifest missing 512x512 icon');
    }

    /** @test */
    public function service_worker_has_proper_cache_strategy()
    {
        $response = $this->get('/sw.js');
        $content = $response->getContent();

        // Check for essential service worker features
        $this->assertStringContainsString('install', $content);
        $this->assertStringContainsString('activate', $content);
        $this->assertStringContainsString('fetch', $content);
        $this->assertStringContainsString('caches.open', $content);
    }

    /** @test */
    public function manifest_respects_configuration()
    {
        config([
            'filament-pwa.app_name' => 'Test Admin Panel',
            'filament-pwa.short_name' => 'TestAdmin',
            'filament-pwa.theme_color' => '#FF0000',
            'filament-pwa.start_url' => '/custom-admin',
        ]);

        $response = $this->get('/manifest.json');
        $manifest = $response->json();

        $this->assertEquals('Test Admin Panel', $manifest['name']);
        $this->assertEquals('TestAdmin', $manifest['short_name']);
        $this->assertEquals('#FF0000', $manifest['theme_color']);
        $this->assertEquals('/custom-admin', $manifest['start_url']);
    }

    /** @test */
    public function service_worker_respects_cache_configuration()
    {
        config([
            'filament-pwa.service_worker.cache_name' => 'test-cache-v1.0.0',
            'filament-pwa.service_worker.offline_url' => '/custom-offline',
        ]);

        $response = $this->get('/sw.js');
        $content = $response->getContent();

        $this->assertStringContainsString('test-cache-v1.0.0', $content);
        $this->assertStringContainsString('/custom-offline', $content);
    }

    /** @test */
    public function browser_config_contains_proper_xml_structure()
    {
        $response = $this->get('/browserconfig.xml');
        $content = $response->getContent();

        // Validate XML structure
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Browser config is not valid XML');

        $this->assertNotNull($xml->msapplication);
        $this->assertNotNull($xml->msapplication->tile);
    }

    /** @test */
    public function offline_page_contains_proper_content()
    {
        $response = $this->get('/offline');
        $content = $response->getContent();

        $this->assertStringContainsString('offline', strtolower($content));
        $this->assertStringContainsString('connection', strtolower($content));

        // Check for essential offline page elements
        $this->assertStringContainsString('<html', $content);
        $this->assertStringContainsString('<head>', $content);
        $this->assertStringContainsString('<body>', $content);
    }
}
