<?php

namespace App\Providers;

use App\Contracts\AlertRepositoryInterface;
use App\Contracts\AlertServiceInterface;
use App\Contracts\AuditServiceInterface;
use App\Contracts\DetectionEngineInterface;
use App\Contracts\IntrusionEventRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\AlertRepository;
use App\Repositories\IntrusionEventRepository;
use App\Repositories\UserRepository;
use App\Services\AlertService;
use App\Services\AuditService;
use App\Services\DetectionEngine;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DetectionEngineInterface::class, DetectionEngine::class);
        $this->app->bind(AlertServiceInterface::class, AlertService::class);
        $this->app->bind(AuditServiceInterface::class, AuditService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(IntrusionEventRepositoryInterface::class, IntrusionEventRepository::class);
        $this->app->bind(AlertRepositoryInterface::class, AlertRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
