<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AventureiroInterface;
use App\Services\AventureiroService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AventureiroInterface::class,
            AventureiroService::class
        );

        $this->app->bind(
            MissoesCompletadasRepositoryInterface::class,
            MissoesCompletadasRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
