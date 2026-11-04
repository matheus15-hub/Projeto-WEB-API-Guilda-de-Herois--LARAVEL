<?php

namespace App\Providers;

use App\Interfaces\AventureiroInterface;
use App\Interfaces\ClasseRepositoryInterface;
use App\Interfaces\GuildaRepositoryInterface;
use App\Interfaces\MissaoRepositoryInterface;
use App\Interfaces\MissoesCompletadasRepositoryInterface;
use App\Interfaces\MonstroRepositoryInterface;
use App\Repositories\AventureiroRepository;
use App\Repositories\ClasseRepository;
use App\Repositories\GuildaRepository;
use App\Repositories\MissaoRepository;
use App\Repositories\MissoesCompletadasRepository;
use App\Repositories\MonstroRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Vincula a AventureiroInterface principal
        $this->app->bind(
            AventureiroInterface::class,
            AventureiroRepository::class
        );

        // Alias de compatibilidade caso alguma classe (como AventureiroService) solicite AventureiroRepositoryInterface
        $this->app->bind(
            'App\Interfaces\AventureiroRepositoryInterface',
            AventureiroRepository::class
        );

        $this->app->bind(
            ClasseRepositoryInterface::class,
            ClasseRepository::class
        );

        $this->app->bind(
            GuildaRepositoryInterface::class,
            GuildaRepository::class
        );

        $this->app->bind(
            MissaoRepositoryInterface::class,
            MissaoRepository::class
        );

        $this->app->bind(
            MissoesCompletadasRepositoryInterface::class,
            MissoesCompletadasRepository::class
        );

        $this->app->bind(
            MonstroRepositoryInterface::class,
            MonstroRepository::class
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