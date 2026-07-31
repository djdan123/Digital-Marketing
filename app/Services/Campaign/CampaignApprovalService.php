<?php

namespace App\Services\Campaign;

use App\Events\CampaignApproved;
use App\Events\CampaignRejected;
use App\Models\Campaign;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignApprovalService
{
    public function __construct(
        private CampaignRepositoryInterface $campaignRepository
    ) {}

    /**
     * Approuve une campagne.
     */
    public function approve(Campaign $campaign, ?string $comments = null): Campaign
    {
        $campaign->update(['status' => 'approved']);
        event(new CampaignApproved($campaign, $comments));
        return $campaign;
    }

    /**
     * Rejette une campagne.
     */
    public function reject(Campaign $campaign, ?string $comments = null): Campaign
    {
        $campaign->update(['status' => 'rejected']);
        event(new CampaignRejected($campaign, $comments));
        return $campaign;
    }

    /**
     * Active une campagne (passage à "active" après paiement et validation).
     */
    public function activate(Campaign $campaign): Campaign
    {
        $campaign->update(['status' => 'active']);
        return $campaign;
    }

    /**
     * Suspend une campagne.
     */
    public function suspend(Campaign $campaign): Campaign
    {
        $campaign->update(['status' => 'paused']);
        return $campaign;
    }
}