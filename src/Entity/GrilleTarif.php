<?php

namespace App\Entity;

use App\Repository\GrilleTarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrilleTarifRepository::class)]
class GrilleTarif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, TarifProduitGrille>
     */
    #[ORM\OneToMany(targetEntity: TarifProduitGrille::class, mappedBy: 'code_grille')]
    private Collection $tarifProduitGrilles;

    /**
     * @var Collection<int, LigneProforma>
     */
    #[ORM\OneToMany(targetEntity: LigneProforma::class, mappedBy: 'code_grille')]
    private Collection $ligneProformas;

    public function __construct()
    {
        $this->tarifProduitGrilles = new ArrayCollection();
        $this->ligneProformas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, TarifProduitGrille>
     */
    public function getTarifProduitGrilles(): Collection
    {
        return $this->tarifProduitGrilles;
    }

    public function addTarifProduitGrille(TarifProduitGrille $tarifProduitGrille): static
    {
        if (!$this->tarifProduitGrilles->contains($tarifProduitGrille)) {
            $this->tarifProduitGrilles->add($tarifProduitGrille);
            $tarifProduitGrille->setCodeGrille($this);
        }

        return $this;
    }

    public function removeTarifProduitGrille(TarifProduitGrille $tarifProduitGrille): static
    {
        if ($this->tarifProduitGrilles->removeElement($tarifProduitGrille)) {
            // set the owning side to null (unless already changed)
            if ($tarifProduitGrille->getCodeGrille() === $this) {
                $tarifProduitGrille->setCodeGrille(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, LigneProforma>
     */
    public function getLigneProformas(): Collection
    {
        return $this->ligneProformas;
    }

    public function addLigneProforma(LigneProforma $ligneProforma): static
    {
        if (!$this->ligneProformas->contains($ligneProforma)) {
            $this->ligneProformas->add($ligneProforma);
            $ligneProforma->setCodeGrille($this);
        }

        return $this;
    }

    public function removeLigneProforma(LigneProforma $ligneProforma): static
    {
        if ($this->ligneProformas->removeElement($ligneProforma)) {
            // set the owning side to null (unless already changed)
            if ($ligneProforma->getCodeGrille() === $this) {
                $ligneProforma->setCodeGrille(null);
            }
        }

        return $this;
    }
}
