<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Notifications\Campaign\CampaignApprovedNotification;
use App\Notifications\Payment\PaymentReceivedNotification;
use App\Notifications\Broadcast\BroadcastStartedNotification;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Envoie une notification à un utilisateur via un canal donné (email, SMS, push).
     */
    public function sendToUser(User $user, mixed $notification, string $channel = 'mail'): void
    {
        // Utiliser le système de notification de Laravel
        // On peut définir des canaux personnalisés si besoin
        $channels = ['mail', 'database']; // par défaut
        if ($channel === 'sms') {
            $channels = ['nexmo']; // exemple
        }

        Notification::send($user, $notification);
    }

    /**
     * Envoie une notification de bienvenue.
     */
    public function sendWelcome(User $user): void
    {
        $this->sendToUser($user, new WelcomeNotification($user));
    }

    /**
     * Notifie l'annonceur que sa campagne est approuvée.
     */
    public function notifyCampaignApproved(User $user, $campaign): void
    {
        $this->sendToUser($user, new CampaignApprovedNotification($campaign));
    }

    /**
     * Notifie l'annonceur que son paiement a été reçu.
     */
    public function notifyPaymentReceived(User $user, $payment): void
    {
        $this->sendToUser($user, new PaymentReceivedNotification($payment));
    }

    /**
     * Notifie le responsable média qu'une diffusion commence.
     */
    public function notifyBroadcastStart(User $user, $broadcast): void
    {
        $this->sendToUser($user, new BroadcastStartedNotification($broadcast));
    }
}