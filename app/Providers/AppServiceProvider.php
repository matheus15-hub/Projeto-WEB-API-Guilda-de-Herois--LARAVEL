<?php

namespace App\Providers;

use App\Interfaces\AventureiroRepositoryInterface;
use App\Interfaces\ClasseRepositoryInterface;
use App\Interfaces\GuildaRepositoryInterface;
use App\Repositories\AventureiroRepository;
use App\Repositories\ClasseRepository;
use App\Repositories\GuildaRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\AventureiroInterface;
use App\Interfaces\MissoesCompletadasRepositoryInterface;
use App\Repositories\MissoesCompletadasRepository;
use App\Services\AventureiroService;

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

        $this->app->bind(AventureiroInterface::class,
         AventureiroService::class);

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
