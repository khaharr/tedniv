<?php
namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $sentAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $recipient;

    #[ORM\ManyToOne(targetEntity: Articles::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $article;

    public function __construct()
    {
        $this->sentAt = new DateTime(); // Initialise la date d'envoi à la création du message
    }

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getSentAt(): ?DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;
        return $this;
    }
}
