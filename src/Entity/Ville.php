<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'villes')]
    private ?Pays $code_pays = null;

    /**
     * @var Collection<int, InfosSociete>
     */
    #[ORM\OneToMany(targetEntity: InfosSociete::class, mappedBy: 'code_ville')]
    private Collection $infosSocietes;

    public function __construct()
    {
        $this->infosSocietes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCodePays(): ?Pays
    {
        return $this->code_pays;
    }

    public function setCodePays(?Pays $code_pays): static
    {
        $this->code_pays = $code_pays;

        return $this;
    }

    /**
     * @return Collection<int, InfosSociete>
     */
    public function getInfosSocietes(): Collection
    {
        return $this->infosSocietes;
    }

    public function addInfosSociete(InfosSociete $infosSociete): static
    {
        if (!$this->infosSocietes->contains($infosSociete)) {
            $this->infosSocietes->add($infosSociete);
            $infosSociete->setCodeVille($this);
        }

        return $this;
    }

    public function removeInfosSociete(InfosSociete $infosSociete): static
    {
        if ($this->infosSocietes->removeElement($infosSociete)) {
            // set the owning side to null (unless already changed)
            if ($infosSociete->getCodeVille() === $this) {
                $infosSociete->setCodeVille(null);
            }
        }

        return $this;
    }
}
