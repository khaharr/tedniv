<?php

namespace App\Entity;

use App\Repository\PortefeuilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortefeuilleRepository::class)]
class Portefeuille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $Solde = null;

    #[ORM\OneToOne(inversedBy: 'portefeuille', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Account = null;

    /**
     * @var Collection<int, Transactions>
     */
    #[ORM\OneToMany(targetEntity: Transactions::class, mappedBy: 'Portefeuille')]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?float
    {
        return $this->Solde;
    }

    public function setSolde(?float $Solde): static
    {
        $this->Solde = $Solde;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->Account;
    }

    public function setAccount(User $Account): static
    {
        $this->Account = $Account;

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setPortefeuille($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPortefeuille() === $this) {
                $transaction->setPortefeuille(null);
            }
        }

        return $this;
    }
}
