<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[ORM\Column]
    private ?float $fav = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pht = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getFav(): ?float
    {
        return $this->fav;
    }

    public function setFav(float $fav): static
    {
        $this->fav = $fav;

        return $this;
    }

    public function getPht(): ?float
    {
        return $this->pht;
    }

    public function setPht(?float $pht): static
    {
        $this->pht = $pht;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
}
