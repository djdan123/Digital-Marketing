<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        // Advertiser sees own payments; accountants/admins see all
        return true;
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($this->before($user, 'view')) {
            return true;
        }

        return isset($payment->advertiser) && $payment->advertiser->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Payments are created by gateway/webhooks, not by users
        return false;
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->before($user, 'update') ?? false;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $this->before($user, 'delete') ?? false;
    }
}
