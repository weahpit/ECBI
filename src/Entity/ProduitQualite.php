<?php

namespace App\Entity;

use App\Repository\ProduitQualiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitQualiteRepository::class)]
class ProduitQualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produitQualites')]
    private ?Produit $code_produit = null;

    #[ORM\ManyToOne(inversedBy: 'produitQualites')]
    private ?Qualite $code_qualite = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodeQualite(): ?Qualite
    {
        return $this->code_qualite;
    }

    public function setCodeQualite(?Qualite $code_qualite): static
    {
        $this->code_qualite = $code_qualite;

        return $this;
    }
}
