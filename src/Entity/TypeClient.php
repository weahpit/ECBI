<?php

namespace App\Entity;

use App\Repository\TypeClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeClientRepository::class)]
class TypeClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, ClientEcbi>
     */
    #[ORM\OneToMany(targetEntity: ClientEcbi::class, mappedBy: 'typeClient')]
    private Collection $cLients;

    public function __construct()
    {
        $this->cLients = new ArrayCollection();
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
     * @return Collection<int, ClientEcbi>
     */
    public function getCLients(): Collection
    {
        return $this->cLients;
    }

    public function addCLient(ClientEcbi $cLient): static
    {
        if (!$this->cLients->contains($cLient)) {
            $this->cLients->add($cLient);
            $cLient->setTypeClient($this);
        }

        return $this;
    }

    public function removeCLient(ClientEcbi $cLient): static
    {
        if ($this->cLients->removeElement($cLient)) {
            // set the owning side to null (unless already changed)
            if ($cLient->getTypeClient() === $this) {
                $cLient->setTypeClient(null);
            }
        }

        return $this;
    }
}
