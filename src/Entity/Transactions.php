<?php

namespace App\Entity;

use App\Enum\TransactionType;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionsRepository::class)]
class Transactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: TransactionType::class)]
    private ?TransactionType $Type = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Portefeuille $Portefeuille = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TransactionType
    {
        return $this->Type;
    }

    public function setType(TransactionType $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getPortefeuille(): ?Portefeuille
    {
        return $this->Portefeuille;
    }

    public function setPortefeuille(?Portefeuille $Portefeuille): static
    {
        $this->Portefeuille = $Portefeuille;

        return $this;
    }
}
