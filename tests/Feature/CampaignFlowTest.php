<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_to_broadcast_flow_is_skeleton()
    {
        $this->markTestSkipped('Integration test skeleton: implement factories and full environment to run.');
    }
}
