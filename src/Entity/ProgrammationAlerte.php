<?php

namespace App\Entity;

use App\Repository\ProgrammationAlerteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammationAlerteRepository::class)]
class ProgrammationAlerte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $type_alerte = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle_alerte = null;

    /**
     * @var Collection<int, AlertesUsers>
     */
    #[ORM\OneToMany(targetEntity: AlertesUsers::class, mappedBy: 'code_alerte')]
    private Collection $alertesUsers;

    public function __construct()
    {
        $this->alertesUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAlerte(): ?int
    {
        return $this->type_alerte;
    }

    public function setTypeAlerte(int $type_alerte): static
    {
        $this->type_alerte = $type_alerte;

        return $this;
    }

    public function getLibelleAlerte(): ?string
    {
        return $this->libelle_alerte;
    }

    public function setLibelleAlerte(string $libelle_alerte): static
    {
        $this->libelle_alerte = $libelle_alerte;

        return $this;
    }

    /**
     * @return Collection<int, AlertesUsers>
     */
    public function getAlertesUsers(): Collection
    {
        return $this->alertesUsers;
    }

    public function addAlertesUser(AlertesUsers $alertesUser): static
    {
        if (!$this->alertesUsers->contains($alertesUser)) {
            $this->alertesUsers->add($alertesUser);
            $alertesUser->setCodeAlerte($this);
        }

        return $this;
    }

    public function removeAlertesUser(AlertesUsers $alertesUser): static
    {
        if ($this->alertesUsers->removeElement($alertesUser)) {
            // set the owning side to null (unless already changed)
            if ($alertesUser->getCodeAlerte() === $this) {
                $alertesUser->setCodeAlerte(null);
            }
        }

        return $this;
    }
}
