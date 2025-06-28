<?php

namespace Alareqi\FilamentPwa\Tests\Unit;

use Alareqi\FilamentPwa\FilamentPwaPlugin;
use Alareqi\FilamentPwa\Tests\TestCase;
use Filament\Panel;

class FilamentPwaPluginTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $plugin = FilamentPwaPlugin::make();

        $this->assertInstanceOf(FilamentPwaPlugin::class, $plugin);
    }

    /** @test */
    public function it_has_correct_id()
    {
        $plugin = FilamentPwaPlugin::make();

        $this->assertEquals('filament-pwa', $plugin->getId());
    }

    /** @test */
    public function it_can_configure_app_name()
    {
        $plugin = FilamentPwaPlugin::make()
            ->appName('Test App');

        $config = $plugin->getConfig();

        $this->assertEquals('Test App', $config['app_name']);
    }

    /** @test */
    public function it_can_configure_short_name()
    {
        $plugin = FilamentPwaPlugin::make()
            ->shortName('TestApp');

        $config = $plugin->getConfig();

        $this->assertEquals('TestApp', $config['short_name']);
    }

    /** @test */
    public function it_can_configure_description()
    {
        $plugin = FilamentPwaPlugin::make()
            ->description('Test Description');

        $config = $plugin->getConfig();

        $this->assertEquals('Test Description', $config['description']);
    }

    /** @test */
    public function it_can_configure_theme_color()
    {
        $plugin = FilamentPwaPlugin::make()
            ->themeColor('#FF0000');

        $config = $plugin->getConfig();

        $this->assertEquals('#FF0000', $config['theme_color']);
    }

    /** @test */
    public function it_can_configure_background_color()
    {
        $plugin = FilamentPwaPlugin::make()
            ->backgroundColor('#FFFFFF');

        $config = $plugin->getConfig();

        $this->assertEquals('#FFFFFF', $config['background_color']);
    }

    /** @test */
    public function it_can_configure_start_url()
    {
        $plugin = FilamentPwaPlugin::make()
            ->startUrl('/custom-admin');

        $config = $plugin->getConfig();

        $this->assertEquals('/custom-admin', $config['start_url']);
    }

    /** @test */
    public function it_can_configure_display_mode()
    {
        $plugin = FilamentPwaPlugin::make()
            ->displayMode('fullscreen');

        $config = $plugin->getConfig();

        $this->assertEquals('fullscreen', $config['display']);
    }

    /** @test */
    public function it_can_configure_orientation()
    {
        $plugin = FilamentPwaPlugin::make()
            ->orientation('landscape');

        $config = $plugin->getConfig();

        $this->assertEquals('landscape', $config['orientation']);
    }

    /** @test */
    public function it_can_configure_scope()
    {
        $plugin = FilamentPwaPlugin::make()
            ->scope('/custom-scope');

        $config = $plugin->getConfig();

        $this->assertEquals('/custom-scope', $config['scope']);
    }

    /** @test */
    public function it_can_configure_language()
    {
        $plugin = FilamentPwaPlugin::make()
            ->language('ar');

        $config = $plugin->getConfig();

        $this->assertEquals('ar', $config['lang']);
    }

    /** @test */
    public function it_can_configure_direction()
    {
        $plugin = FilamentPwaPlugin::make()
            ->direction('rtl');

        $config = $plugin->getConfig();

        $this->assertEquals('rtl', $config['dir']);
    }

    /** @test */
    public function it_can_configure_categories()
    {
        $categories = ['productivity', 'business'];

        $plugin = FilamentPwaPlugin::make()
            ->categories($categories);

        $config = $plugin->getConfig();

        $this->assertEquals($categories, $config['categories']);
    }

    /** @test */
    public function it_can_configure_shortcuts()
    {
        $shortcuts = [
            [
                'name' => 'Dashboard',
                'url' => '/admin/dashboard',
            ],
        ];

        $plugin = FilamentPwaPlugin::make()
            ->shortcuts($shortcuts);

        $config = $plugin->getConfig();

        $this->assertEquals($shortcuts, $config['shortcuts']);
    }

    /** @test */
    public function it_can_configure_installation_prompts()
    {
        $plugin = FilamentPwaPlugin::make()
            ->installationPrompts(false);

        $config = $plugin->getConfig();

        $this->assertFalse($config['installation_prompts']);
    }

    /** @test */
    public function it_can_chain_configuration_methods()
    {
        $plugin = FilamentPwaPlugin::make()
            ->appName('Chained App')
            ->shortName('Chained')
            ->themeColor('#FF0000')
            ->backgroundColor('#FFFFFF')
            ->startUrl('/chained')
            ->displayMode('standalone')
            ->orientation('portrait')
            ->scope('/chained')
            ->language('en')
            ->direction('ltr')
            ->installationPrompts(true);

        $config = $plugin->getConfig();

        $this->assertEquals('Chained App', $config['app_name']);
        $this->assertEquals('Chained', $config['short_name']);
        $this->assertEquals('#FF0000', $config['theme_color']);
        $this->assertEquals('#FFFFFF', $config['background_color']);
        $this->assertEquals('/chained', $config['start_url']);
        $this->assertEquals('standalone', $config['display']);
        $this->assertEquals('portrait', $config['orientation']);
        $this->assertEquals('/chained', $config['scope']);
        $this->assertEquals('en', $config['lang']);
        $this->assertEquals('ltr', $config['dir']);
        $this->assertTrue($config['installation_prompts']);
    }

    /** @test */
    public function it_returns_empty_config_by_default()
    {
        $plugin = FilamentPwaPlugin::make();
        $config = $plugin->getConfig();

        $this->assertIsArray($config);
        $this->assertEmpty($config);
    }

    /** @test */
    public function it_can_be_registered_with_panel()
    {
        $panel = Panel::make();
        $plugin = FilamentPwaPlugin::make();

        // This should not throw an exception
        $plugin->register($panel);

        $this->assertTrue(true); // If we get here, registration worked
    }

    /** @test */
    public function it_can_be_booted_with_panel()
    {
        $panel = Panel::make();
        $plugin = FilamentPwaPlugin::make();

        // This should not throw an exception
        $plugin->boot($panel);

        $this->assertTrue(true); // If we get here, boot worked
    }
}
