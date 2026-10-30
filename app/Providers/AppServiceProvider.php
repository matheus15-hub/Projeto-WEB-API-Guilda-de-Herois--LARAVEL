<?php

namespace App\Providers;

use App\Interfaces\ClasseRepositoryInterface;
use App\Interfaces\GuildaRepositoryInterface;
use App\Repositories\ClasseRepository;
use App\Repositories\GuildaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClasseRepositoryInterface::class,
            ClasseRepository::class
        );

        $this->app->bind(
            GuildaRepositoryInterface::class,
            GuildaRepository::class
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
