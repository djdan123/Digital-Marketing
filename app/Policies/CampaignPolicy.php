<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Campaign;

class CampaignPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        // Toute personne authentifiée peut lister, les filtres se font au contrôleur
        return true;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        // Admins autorisés via before(), sinon propriétaire annonceur
        return $this->isAdvertiserOwner($user, $campaign);
    }

    public function create(User $user): bool
    {
        // Seuls les annonceurs peuvent créer une campagne
        return isset($user->role) && $user->role === \App\Enums\UserRole::ADVERTISER->value;
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $this->isAdvertiserOwner($user, $campaign);
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $this->isAdvertiserOwner($user, $campaign);
    }
}
