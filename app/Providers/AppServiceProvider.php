<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment('local') && config('database.default') === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');

            if ($databasePath !== ':memory:' && ! file_exists($databasePath)) {
                $databaseDirectory = dirname($databasePath);

                if (! is_dir($databaseDirectory)) {
                    mkdir($databaseDirectory, 0755, true);
                }

                touch($databasePath);
            }
        }
    }
}
