<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Advertiser;
use App\Models\Campaign;
use App\Policies\CampaignPolicy;

$user = new User(['id' => 42, 'role' => 'advertiser']);
$advertiser = new Advertiser(['id' => 5, 'user_id' => 42]);
$campaign = new Campaign();
$campaign->setRelation('advertiser', $advertiser);

$policy = new CampaignPolicy();

var_dump('user->id', $user->id);
var_dump('advertiser->user_id', $campaign->advertiser->user_id ?? null);
var_dump('policy->view', $policy->view($user, $campaign));

