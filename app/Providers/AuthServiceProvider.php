<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Company;
use App\Models\Campaign;
use App\Models\Advertisement;
use App\Models\Media;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Advertiser;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\CampaignPolicy;
use App\Policies\AdvertisementPolicy;
use App\Policies\MediaPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\AdvertiserPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        Campaign::class => CampaignPolicy::class,
        Advertisement::class => AdvertisementPolicy::class,
        Media::class => MediaPolicy::class,
        Payment::class => PaymentPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Advertiser::class => AdvertiserPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
