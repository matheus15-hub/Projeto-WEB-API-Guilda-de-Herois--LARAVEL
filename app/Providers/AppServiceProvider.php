<?php

namespace App\Providers;

use App\Interfaces\ClasseRepositoryInterface;
use App\Repositories\ClasseRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
