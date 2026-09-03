<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Ville>
     */
    #[ORM\OneToMany(targetEntity: Ville::class, mappedBy: 'code_pays')]
    private Collection $villes;

    /**
     * @var Collection<int, ClientEcbi>
     */
    #[ORM\OneToMany(targetEntity: ClientEcbi::class, mappedBy: 'code_pays')]
    private Collection $clientEcbis;

    public function __construct()
    {
        $this->villes = new ArrayCollection();
        $this->clientEcbis = new ArrayCollection();
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

    /**
     * @return Collection<int, Ville>
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): static
    {
        if (!$this->villes->contains($ville)) {
            $this->villes->add($ville);
            $ville->setCodePays($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): static
    {
        if ($this->villes->removeElement($ville)) {
            // set the owning side to null (unless already changed)
            if ($ville->getCodePays() === $this) {
                $ville->setCodePays(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientEcbi>
     */
    public function getClientEcbis(): Collection
    {
        return $this->clientEcbis;
    }

    public function addClientEcbi(ClientEcbi $clientEcbi): static
    {
        if (!$this->clientEcbis->contains($clientEcbi)) {
            $this->clientEcbis->add($clientEcbi);
            $clientEcbi->setCodePays($this);
        }

        return $this;
    }

    public function removeClientEcbi(ClientEcbi $clientEcbi): static
    {
        if ($this->clientEcbis->removeElement($clientEcbi)) {
            // set the owning side to null (unless already changed)
            if ($clientEcbi->getCodePays() === $this) {
                $clientEcbi->setCodePays(null);
            }
        }

        return $this;
    }
}
