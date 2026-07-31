<?php

namespace App\Services\Contracts;

use App\Models\User;

interface NotificationServiceInterface extends ServiceInterface
{
    /**
     * Envoie une notification à un utilisateur sur un canal donné.
     */
    public function sendToUser(User $user, mixed $notification, string $channel = 'mail'): void;

    /**
     * Envoie une notification de bienvenue.
     */
    public function sendWelcome(User $user): void;

    /**
     * Notifie l'annonceur que sa campagne est approuvée.
     */
    public function notifyCampaignApproved(User $user, $campaign): void;

    /**
     * Notifie l'annonceur que son paiement a été reçu.
     */
    public function notifyPaymentReceived(User $user, $payment): void;

    /**
     * Notifie le responsable média qu'une diffusion commence.
     */
    public function notifyBroadcastStart(User $user, $broadcast): void;
}