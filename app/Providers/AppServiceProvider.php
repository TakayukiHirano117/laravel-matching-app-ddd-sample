<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infra\Repository\UserRepository;
use App\Domain\Repository\LikeRepositoryInterface;
use App\Infra\Repository\LikeRepository;
use App\UseCase\QueryService\UserQueryServiceInterface;
use App\Infra\QueryService\UserQueryService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // リポジトリのバインディング
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            LikeRepositoryInterface::class,
            LikeRepository::class
        );

        // QueryServiceのバインディング
        $this->app->bind(
            UserQueryServiceInterface::class,
            UserQueryService::class
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
