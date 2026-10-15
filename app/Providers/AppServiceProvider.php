<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fallback APP_KEY if missing in cloud deployment environment variables
        if (empty(config('app.key'))) {
            config(['app.key' => 'base64:9u5ClZ+qDebsfCvhsmt65K639DQWn0zxzSH0mOCPmkQ=']);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            $dbPath = database_path('database.sqlite');
            if (!file_exists($dbPath)) {
                @touch($dbPath);
            }

            // Self-healing: auto-migrate and seed if tables are missing in fresh cloud deployment
            if (!Schema::hasTable('movies')) {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            Log::error('Self-healing database initialization: ' . $e->getMessage());
        }
    }
}
