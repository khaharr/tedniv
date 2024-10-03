<?php

namespace App\Service;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationService
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Envoie une notification simple à un utilisateur par email.
     *
     * @param string $message Le contenu du message.
     * @param UserInterface|null $user L'utilisateur à notifier (facultatif).
     */
    public function sendNotification(string $message, UserInterface $user = null): void
    {
        // Crée une notification avec le titre et le message spécifié
        $notification = new Notification('Notification de Porte-Monnaie', ['email']);
        $notification->content($message);

        // Si un utilisateur est fourni, utilise son email comme destinataire
        if ($user !== null && $user->getEmail()) {
            $recipient = new Recipient($user->getEmail());
            $this->notifier->send($notification, $recipient);
        } else {
            // Si aucun destinataire n'est fourni, envoie simplement la notification à la configuration par défaut
            $this->notifier->send($notification);
        }
    }
}
