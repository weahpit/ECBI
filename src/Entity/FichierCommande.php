<?php

namespace App\Entity;

use App\Repository\FichierCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierCommandeRepository::class)]
class FichierCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename = null;

    #[ORM\ManyToOne(inversedBy: 'fichierCommandes')]
    private ?Commande $code_commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getCodeCommande(): ?Commande
    {
        return $this->code_commande;
    }

    public function setCodeCommande(?Commande $code_commande): static
    {
        $this->code_commande = $code_commande;

        return $this;
    }
}
