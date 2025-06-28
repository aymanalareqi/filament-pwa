<?php

namespace Alareqi\FilamentPwa;

use Alareqi\FilamentPwa\Commands\FilamentPwaSetupCommand;
use Alareqi\FilamentPwa\Http\Controllers\PwaController;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPwaServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-pwa';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasCommands([
                FilamentPwaSetupCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        // Register publishable assets
        $this->publishes([
            __DIR__ . '/../config/filament-pwa.php' => config_path('filament-pwa.php'),
        ], 'filament-pwa-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-pwa'),
        ], 'filament-pwa-views');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/filament-pwa'),
        ], 'filament-pwa-assets');
    }

    public function packageBooted(): void
    {
        // Register PWA routes
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'middleware' => config('filament-pwa.route_middleware', ['web']),
        ], function () {
            // PWA Manifest
            Route::get('/manifest.json', [PwaController::class, 'manifest'])
                ->name('filament-pwa.manifest');

            // Service Worker
            Route::get('/sw.js', [PwaController::class, 'serviceWorker'])
                ->name('filament-pwa.service-worker');

            // Browser Config (Microsoft Tiles)
            Route::get('/browserconfig.xml', [PwaController::class, 'browserConfig'])
                ->name('filament-pwa.browser-config');

            // Offline fallback page
            Route::get('/offline', [PwaController::class, 'offline'])
                ->name('filament-pwa.offline');
        });
    }
}
