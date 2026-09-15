<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $code_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, LigneProforma>
     */
    #[ORM\OneToMany(targetEntity: LigneProforma::class, mappedBy: 'code_produit')]
    private Collection $ligneProformas;

    /**
     * @var Collection<int, TarifProduitGrille>
     */
    #[ORM\OneToMany(targetEntity: TarifProduitGrille::class, mappedBy: 'code_produit')]
    private Collection $tarifProduitGrilles;

    /**
     * @var Collection<int, ProduitQualite>
     */
    #[ORM\OneToMany(targetEntity: ProduitQualite::class, mappedBy: 'code_produit')]
    private Collection $produitQualites;

    /**
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'code_produit')]
    private Collection $ligneCommandes;

    public function __construct()
    {
        $this->ligneProformas = new ArrayCollection();
        $this->tarifProduitGrilles = new ArrayCollection();
        $this->produitQualites = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeProduit(): ?string
    {
        return $this->code_produit;
    }

    public function setCodeProduit(string $code_produit): static
    {
        $this->code_produit = $code_produit;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $ligneProforma->setCodeProduit($this);
        }

        return $this;
    }

    public function removeLigneProforma(LigneProforma $ligneProforma): static
    {
        if ($this->ligneProformas->removeElement($ligneProforma)) {
            // set the owning side to null (unless already changed)
            if ($ligneProforma->getCodeProduit() === $this) {
                $ligneProforma->setCodeProduit(null);
            }
        }

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
            $tarifProduitGrille->setCodeProduit($this);
        }

        return $this;
    }

    public function removeTarifProduitGrille(TarifProduitGrille $tarifProduitGrille): static
    {
        if ($this->tarifProduitGrilles->removeElement($tarifProduitGrille)) {
            // set the owning side to null (unless already changed)
            if ($tarifProduitGrille->getCodeProduit() === $this) {
                $tarifProduitGrille->setCodeProduit(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, ProduitQualite>
     */
    public function getProduitQualites(): Collection
    {
        return $this->produitQualites;
    }

    public function addProduitQualite(ProduitQualite $produitQualite): static
    {
        if (!$this->produitQualites->contains($produitQualite)) {
            $this->produitQualites->add($produitQualite);
            $produitQualite->setCodeProduit($this);
        }

        return $this;
    }

    public function removeProduitQualite(ProduitQualite $produitQualite): static
    {
        if ($this->produitQualites->removeElement($produitQualite)) {
            // set the owning side to null (unless already changed)
            if ($produitQualite->getCodeProduit() === $this) {
                $produitQualite->setCodeProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): static
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->add($ligneCommande);
            $ligneCommande->setCodeProduit($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): static
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getCodeProduit() === $this) {
                $ligneCommande->setCodeProduit(null);
            }
        }

        return $this;
    }
}
