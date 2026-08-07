<?php

namespace Tests\Feature;

use App\DTOs\Payment\ProcessPaymentDTO;
use App\Models\Advertiser;
use App\Models\Campaign;
use App\Models\Transaction;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformCommissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_payment_creates_platform_commission(): void
    {
        $advertiser = Advertiser::factory()->create();
        $campaign = Campaign::factory()->create(['advertiser_id' => $advertiser->id]);

        $service = app(PaymentServiceInterface::class);
        $payment = $service->process(new ProcessPaymentDTO(
            advertiser_id: $advertiser->id,
            campaign_id: $campaign->id,
            amount: '100000',
            currency: 'FBU',
            payment_method: 'stripe',
            status: 'completed',
            reference: 'pay_test_1',
            metadata: []
        ));

        $this->assertDatabaseHas('transactions', [
            'payment_id' => $payment->id,
            'type' => 'commission',
            'currency' => 'FBU',
        ]);

        $commission = Transaction::where('payment_id', $payment->id)->where('type', 'commission')->first();
        $this->assertNotNull($commission);
        $this->assertEquals(15000.0, (float) $commission->amount);
    }
}
