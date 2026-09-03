<?php

namespace App\Entity;

use App\Repository\LigneProformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneProformaRepository::class)]
class LigneProforma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneProformas')]
    private ?Proforma $code_proforma = null;

    #[ORM\ManyToOne(inversedBy: 'ligneProformas')]
    private ?Produit $code_produit = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\ManyToOne(inversedBy: 'ligneProformas')]
    private ?GrilleTarif $code_grille = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeProforma(): ?Proforma
    {
        return $this->code_proforma;
    }

    public function setCodeProforma(?Proforma $code_proforma): static
    {
        $this->code_proforma = $code_proforma;

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

    public function getCodeGrille(): ?GrilleTarif
    {
        return $this->code_grille;
    }

    public function setCodeGrille(?GrilleTarif $code_grille): static
    {
        $this->code_grille = $code_grille;

        return $this;
    }
}
