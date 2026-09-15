<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCommandeRepository::class)]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneCommandes')]
    private ?Commande $code_commande = null;

    #[ORM\ManyToOne(inversedBy: 'ligneCommandes')]
    private ?Produit $code_produit = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCommande(): ?Commande
    {
        return $this->code_commande;
    }

    public function setCodeCommande(?Commande $code_commande): static
    {
        $this->code_commande = $code_commande;

        return $this;
    }

    public function getCodeProduit(): ?Produit
    {
        return $this->code_produit;
    }

    public function setCodeProduit(?Produit $code_produit): static
    {
        $this->code_produit = $code_produit;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): static
    {
        $this->total = $total;

        return $this;
    }
}
