<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infra\Repository\UserRepository;
use App\Domain\Repository\LikeRepositoryInterface;
use App\Infra\Repository\LikeRepository;
use App\UseCase\QueryService\UserQueryServiceInterface;
use App\Infra\QueryService\UserQueryService;
use App\Domain\DomainService\LikeDomainServiceInterface;
use App\Infra\DomainService\LikeDomainService;
use App\Domain\DomainService\MatchingDomainServiceInterface;
use App\Infra\DomainService\MatchingDomainService;
use App\Domain\Repository\MatchingRepositoryInterface;
use App\Infra\Repository\MatchingRepository;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Infra\Repository\TransactionRepository;
use App\Domain\Repository\TalkRoomRepositoryInterface;
use App\Infra\Repository\TalkRoomRepository;
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
        $this->app->bind(
            MatchingRepositoryInterface::class,
            MatchingRepository::class
        );
        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
        $this->app->bind(
            TalkRoomRepositoryInterface::class,
            TalkRoomRepository::class
        );
        // QueryServiceのバインディング
        $this->app->bind(
            UserQueryServiceInterface::class,
            UserQueryService::class
        );

        // DomainServiceのバインディング
        $this->app->bind(
            LikeDomainServiceInterface::class,
            LikeDomainService::class
        );
        $this->app->bind(
            MatchingDomainServiceInterface::class,
            MatchingDomainService::class
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
