<?php
namespace App\Service;

use App\Entity\Messages;
use App\Entity\User;
use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Envoie un message
    public function sendMessage(User $sender, User $recipient, Articles $article, string $content): Messages
    {
        $message = new Messages();
        $message->setSender($sender)
                ->setRecipient($recipient)
                ->setArticle($article)
                ->setContent($content);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }

    // Récupère les messages entre deux utilisateurs (ou seulement avec le destinataire si $sender est null)
    public function getMessages(?User $sender, User $recipient, Articles $article): array
    {
        $criteria = [
            'recipient' => $recipient,
            'article' => $article
        ];

        if ($sender) {
            // Si le sender est défini, on récupère les messages entre le sender et le recipient
            $criteria['sender'] = $sender;
        }

        return $this->entityManager->getRepository(Messages::class)
            ->findBy($criteria, ['sentAt' => 'ASC']);
    }
}
