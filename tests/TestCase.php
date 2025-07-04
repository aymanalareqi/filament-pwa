<?php

namespace Alareqi\FilamentPwa\Tests;

use Alareqi\FilamentPwa\FilamentPwaServiceProvider;
use Filament\FilamentServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Alareqi\\FilamentPwa\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentServiceProvider::class,
            FilamentPwaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-pwa_table.php.stub';
        $migration->up();
        */
    }
}
