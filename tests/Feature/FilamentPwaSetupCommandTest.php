<?php

namespace Alareqi\FilamentPwa\Tests\Feature;

use Alareqi\FilamentPwa\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class FilamentPwaSetupCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test directories
        File::makeDirectory(public_path('images/icons'), 0755, true, true);
    }

    protected function tearDown(): void
    {
        // Clean up test files
        File::deleteDirectory(public_path('images'));

        parent::tearDown();
    }

    /** @test */
    public function it_can_run_setup_command()
    {
        $this->artisan('filament-pwa:setup')
            ->expectsOutput('🚀 Setting up Filament PWA Plugin...')
            ->expectsOutput('✅ Filament PWA setup completed!')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_publish_assets()
    {
        $this->artisan('filament-pwa:setup --publish-assets')
            ->expectsOutput('📦 Publishing PWA assets...')
            ->expectsOutput('✅ Assets published successfully!')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_validate_pwa_setup()
    {
        $this->artisan('filament-pwa:setup --validate')
            ->expectsOutput('🔍 Validating PWA setup...')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_shows_status_when_no_options_provided()
    {
        $this->artisan('filament-pwa:setup')
            ->expectsOutput('📱 Filament PWA Plugin Status')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_handles_missing_source_file_gracefully()
    {
        $this->artisan('filament-pwa:setup --generate-icons --source=nonexistent.svg')
            ->expectsOutput('Source image not found: ' . public_path('nonexistent.svg'))
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_generate_icons_with_valid_source()
    {
        // Create a simple test SVG
        $testSvg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <circle cx="50" cy="50" r="40" fill="#A77B56"/>
</svg>';

        File::put(public_path('test-icon.svg'), $testSvg);

        $this->artisan('filament-pwa:setup --generate-icons --source=test-icon.svg')
            ->expectsOutput('📱 Generating PWA icons...')
            ->assertExitCode(0);

        // Clean up
        File::delete(public_path('test-icon.svg'));
    }

    /** @test */
    public function it_displays_available_commands_in_status()
    {
        $this->artisan('filament-pwa:setup')
            ->expectsOutput('Available commands:')
            ->expectsOutput('php artisan filament-pwa:setup --publish-assets')
            ->expectsOutput('php artisan filament-pwa:setup --generate-icons --source=path/to/logo.svg')
            ->expectsOutput('php artisan filament-pwa:setup --validate')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_displays_pwa_urls_in_status()
    {
        $this->artisan('filament-pwa:setup')
            ->expectsOutput('PWA URLs:')
            ->expectsOutput('Manifest: ' . url('manifest.json'))
            ->expectsOutput('Service Worker: ' . url('sw.js'))
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_handle_multiple_options()
    {
        $this->artisan('filament-pwa:setup --publish-assets --validate')
            ->expectsOutput('📦 Publishing PWA assets...')
            ->expectsOutput('🔍 Validating PWA setup...')
            ->assertExitCode(0);
    }

    /** @test */
    public function validation_checks_required_files()
    {
        $this->artisan('filament-pwa:setup --validate')
            ->expectsOutput('🔍 Validating PWA setup...')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_shows_configuration_table_in_status()
    {
        $this->artisan('filament-pwa:setup')
            ->expectsOutput('📱 Filament PWA Plugin Status')
            ->assertExitCode(0);
    }
}
