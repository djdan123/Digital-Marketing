<?php

namespace App\Services\Contracts;

use App\Models\Campaign;

interface CampaignApprovalServiceInterface extends ServiceInterface
{
    /**
     * Approuve une campagne.
     */
    public function approve(Campaign $campaign, ?string $comments = null): Campaign;

    /**
     * Rejette une campagne.
     */
    public function reject(Campaign $campaign, ?string $comments = null): Campaign;

    /**
     * Active une campagne (passage à "active").
     */
    public function activate(Campaign $campaign): Campaign;

    /**
     * Suspend une campagne (passage à "paused").
     */
    public function suspend(Campaign $campaign): Campaign;
}