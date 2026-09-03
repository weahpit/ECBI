<?php

namespace App\Entity;

use App\Repository\OptionsEcbiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionsEcbiRepository::class)]
class OptionsEcbi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $classname = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 50)]
    private ?string $type_option = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $groupe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassname(): ?string
    {
        return $this->classname;
    }

    public function setClassname(string $classname): static
    {
        $this->classname = $classname;

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

    public function getTypeOption(): ?string
    {
        return $this->type_option;
    }

    public function setTypeOption(string $type_option): static
    {
        $this->type_option = $type_option;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }
}
