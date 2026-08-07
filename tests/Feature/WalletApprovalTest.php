<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_wallet_request(): void
    {
        $advertiser = User::factory()->create(['role' => 'annonceur']);
        $admin = User::factory()->create(['role' => 'admin']);

        $request = Report::create([
            'advertiser_id' => $advertiser->id,
            'title' => 'Demande de recharge',
            'description' => 'Recharge de test',
            'type' => 'wallet_request',
            'filters' => ['amount' => 15000],
            'results' => ['status' => 'pending'],
        ]);

        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/admin/wallet/requests/' . $request->id . '/approve');

        $response->assertOk();
        $request->refresh();

        $this->assertSame('approved', $request->results['status'] ?? null);
        $this->assertDatabaseHas('payments', [
            'advertiser_id' => $advertiser->id,
            'amount' => 15000,
            'status' => 'completed',
        ]);
    }
}
