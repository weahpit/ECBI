<?php

namespace App\Entity;

use App\Repository\AlertesUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertesUsersRepository::class)]
class AlertesUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'alertesUsers')]
    private ?User $code_user = null;

    #[ORM\ManyToOne(inversedBy: 'alertesUsers')]
    private ?ProgrammationAlerte $code_alerte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeUser(): ?User
    {
        return $this->code_user;
    }

    public function setCodeUser(?User $code_user): static
    {
        $this->code_user = $code_user;

        return $this;
    }

    public function getCodeAlerte(): ?ProgrammationAlerte
    {
        return $this->code_alerte;
    }

    public function setCodeAlerte(?ProgrammationAlerte $code_alerte): static
    {
        $this->code_alerte = $code_alerte;

        return $this;
    }
}
