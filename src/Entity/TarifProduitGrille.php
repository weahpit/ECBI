<?php

namespace App\Entity;

use App\Repository\TarifProduitGrilleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifProduitGrilleRepository::class)]
class TarifProduitGrille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tarifProduitGrilles')]
    private ?Produit $code_produit = null;

    #[ORM\ManyToOne(inversedBy: 'tarifProduitGrilles')]
    private ?Grilletarif $code_grille = null;

    #[ORM\Column]
    private ?float $tarif = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $cretated_by = null;

    #[ORM\Column(nullable: true)]
    private ?bool $en_vigueur = null;

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
    public function getCodeGrille(): ?GrilleTarif
    {
        return $this->code_grille;
    }
    public function setCodeGrille(?GrilleTarif $code_grille): static
    {
        $this->code_grille = $code_grille;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCretatedBy(): ?string
    {
        return $this->cretated_by;
    }

    public function setCretatedBy(string $cretated_by): static
    {
        $this->cretated_by = $cretated_by;

        return $this;
    }

    public function isEnVigueur(): ?bool
    {
        return $this->en_vigueur;
    }

    public function setEnVigueur(?bool $en_vigueur): static
    {
        $this->en_vigueur = $en_vigueur;

        return $this;
    }
}
