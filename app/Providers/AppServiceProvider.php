<?php

namespace App\Providers;

// Repositories Interfaces
use App\Repositories\Contracts\AdvertisementRepositoryInterface;
use App\Repositories\Contracts\BroadcastRepositoryInterface;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\StatisticRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

// Repositories Eloquent
use App\Repositories\Eloquent\AdvertisementRepository;
use App\Repositories\Eloquent\BroadcastRepository;
use App\Repositories\Eloquent\CampaignRepository;
use App\Repositories\Eloquent\InvoiceRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ReportRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\StatisticRepository;
use App\Repositories\Eloquent\UserRepository;

// Services Contracts
use App\Services\Contracts\AdvertisementServiceInterface;
use App\Services\Contracts\AdvertisementValidationServiceInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\BroadcastExecutionServiceInterface;
use App\Services\Contracts\BroadcastSchedulingServiceInterface;
use App\Services\Contracts\CampaignApprovalServiceInterface;
use App\Services\Contracts\CampaignPricingServiceInterface;
use App\Services\Contracts\CampaignServiceInterface;
use App\Services\Contracts\FileUploadServiceInterface;
use App\Services\Contracts\InvoiceServiceInterface;
use App\Services\Contracts\MediaServiceInterface;
use App\Services\Contracts\NotificationServiceInterface;
use App\Services\Contracts\PaymentCommissionServiceInterface;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\Contracts\ReportServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Services\Contracts\StatisticAggregationServiceInterface;
use App\Services\Contracts\UserServiceInterface;

// Services Implementations
use App\Services\Advertisement\AdvertisementService;
use App\Services\Advertisement\AdvertisementValidationService;
use App\Services\Auth\AuthService;
use App\Services\Broadcast\BroadcastExecutionService;
use App\Services\Broadcast\BroadcastSchedulingService;
use App\Services\Campaign\CampaignApprovalService;
use App\Services\Campaign\CampaignPricingService;
use App\Services\Campaign\CampaignService;
use App\Services\File\FileUploadService;
use App\Services\Media\MediaService;
use App\Services\Notification\NotificationService;
use App\Services\Payment\InvoiceService;
use App\Services\Payment\PaymentCommissionService;
use App\Services\Payment\PaymentService;
use App\Services\Report\ReportService;
use App\Services\Setting\SettingService;
use App\Services\Statistic\StatisticAggregationService;
use App\Services\User\UserService;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // === REPOSITORIES ===
        // Existants
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(AdvertisementRepositoryInterface::class, AdvertisementRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Nouveaux (à créer)
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(BroadcastRepositoryInterface::class, BroadcastRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(StatisticRepositoryInterface::class, StatisticRepository::class);

        // === SERVICES ===
        // Existants
        $this->app->bind(CampaignServiceInterface::class, CampaignService::class);
        $this->app->bind(AdvertisementServiceInterface::class, AdvertisementService::class);
        $this->app->bind(MediaServiceInterface::class, MediaService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Nouveaux
        $this->app->bind(AdvertisementValidationServiceInterface::class, AdvertisementValidationService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(BroadcastExecutionServiceInterface::class, BroadcastExecutionService::class);
        $this->app->bind(BroadcastSchedulingServiceInterface::class, BroadcastSchedulingService::class);
        $this->app->bind(CampaignApprovalServiceInterface::class, CampaignApprovalService::class);
        $this->app->bind(CampaignPricingServiceInterface::class, CampaignPricingService::class);
        $this->app->bind(FileUploadServiceInterface::class, FileUploadService::class);
        $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(PaymentCommissionServiceInterface::class, PaymentCommissionService::class);
        $this->app->bind(ReportServiceInterface::class, ReportService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(StatisticAggregationServiceInterface::class, StatisticAggregationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Réduire la longueur par défaut des index (pour MySQL < 5.7.7)
        Schema::defaultStringLength(191);

        // Définir l'URL de réinitialisation du mot de passe pour l'API
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}