<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Advertiser;
use App\Models\Campaign;
use App\Models\Advertisement;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Media;
use App\Models\Payment;
use App\Models\Schedule;
use App\Policies\CampaignPolicy;
use App\Policies\AdvertisementPolicy;
use App\Policies\AdvertiserPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MediaPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\UserPolicy;

class PoliciesTest extends TestCase
{
    public function test_admin_before_all_policies_returns_true()
    {
        $admin = new User();
        $admin->id = 1;
        $admin->role = \App\Enums\UserRole::ADMIN->value;

        $policies = [
            new CampaignPolicy(),
            new AdvertisementPolicy(),
            new AdvertiserPolicy(),
            new CompanyPolicy(),
            new InvoicePolicy(),
            new MediaPolicy(),
            new PaymentPolicy(),
            new SchedulePolicy(),
            new UserPolicy(),
        ];

        foreach ($policies as $policy) {
            $this->assertTrue($policy->before($admin, 'any'));
        }
    }

    public function test_campaign_policy_owner_checks()
    {
        $user = new User(['id' => 42, 'role' => \App\Enums\UserRole::ADVERTISER->value]);

        $advertiser = new Advertiser(['id' => 5, 'user_id' => 42]);
        $campaign = new Campaign();
        $campaign->setRelation('advertiser', $advertiser);

        $policy = new CampaignPolicy();

        $this->assertTrue($policy->view($user, $campaign));
        $this->assertTrue($policy->update($user, $campaign));
        $this->assertTrue($policy->delete($user, $campaign));
    }

    public function test_campaign_policy_non_owner_fails()
    {
        $user = new User(['id' => 99, 'role' => \App\Enums\UserRole::ADVERTISER->value]);

        $advertiser = new Advertiser(['id' => 5, 'user_id' => 42]);
        $campaign = new Campaign();
        $campaign->setRelation('advertiser', $advertiser);

        $policy = new CampaignPolicy();

        $this->assertFalse($policy->update($user, $campaign));
    }

    public function test_advertisement_policy_owner_and_media_manager()
    {
        $user = new User(['id' => 42, 'role' => \App\Enums\UserRole::ADVERTISER->value]);
        $advertiser = new Advertiser(['id' => 5, 'user_id' => 42]);

        $ad = new Advertisement();
        $ad->setRelation('advertiser', $advertiser);

        $policy = new AdvertisementPolicy();

        $this->assertTrue($policy->view($user, $ad));
        $this->assertTrue($policy->update($user, $ad));

        // Media manager should be allowed to view
        $mediaManager = new User(['id' => 7, 'role' => \App\Enums\UserRole::MEDIA_MANAGER->value]);
        $this->assertTrue($policy->view($mediaManager, $ad));
    }

    public function test_user_policy_self_access()
    {
        $user = new User(['id' => 10, 'role' => \App\Enums\UserRole::ADVERTISER->value]);

        $policy = new UserPolicy();

        $this->assertTrue($policy->view($user, $user));
        $this->assertTrue($policy->update($user, $user));
    }

    public function test_media_policy_company_owner()
    {
        $user = new User(['id' => 20, 'company_id' => 2, 'role' => \App\Enums\UserRole::MEDIA_MANAGER->value]);
        $media = new Media(['company_id' => 2]);

        $policy = new MediaPolicy();

        $this->assertTrue($policy->update($user, $media));
        $this->assertTrue($policy->delete($user, $media));
    }

    public function test_payment_policy_advertiser_owner()
    {
        $user = new User(['id' => 42, 'role' => \App\Enums\UserRole::ADVERTISER->value]);
        $advertiser = new Advertiser(['id' => 5, 'user_id' => 42]);
        $payment = new Payment();
        $payment->setRelation('advertiser', $advertiser);

        $policy = new PaymentPolicy();

        $this->assertTrue($policy->view($user, $payment));
    }

    public function test_schedule_policy_media_company()
    {
        $user = new User(['id' => 21, 'company_id' => 3, 'role' => \App\Enums\UserRole::MEDIA_MANAGER->value]);
        $media = new Media(['company_id' => 3]);
        $schedule = new Schedule();
        $schedule->setRelation('media', $media);

        $policy = new SchedulePolicy();

        $this->assertTrue($policy->view($user, $schedule));
    }
}
