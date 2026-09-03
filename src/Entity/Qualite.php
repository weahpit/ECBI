<?php

namespace App\Entity;

use App\Repository\QualiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualiteRepository::class)]
class Qualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, ProduitQualite>
     */
    #[ORM\OneToMany(targetEntity: ProduitQualite::class, mappedBy: 'code_qualite')]
    private Collection $produitQualites;

    public function __construct()
    {
        $this->produitQualites = new ArrayCollection();
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
            $produitQualite->setCodeQualite($this);
        }

        return $this;
    }

    public function removeProduitQualite(ProduitQualite $produitQualite): static
    {
        if ($this->produitQualites->removeElement($produitQualite)) {
            // set the owning side to null (unless already changed)
            if ($produitQualite->getCodeQualite() === $this) {
                $produitQualite->setCodeQualite(null);
            }
        }

        return $this;
    }
}
