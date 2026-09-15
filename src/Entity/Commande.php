<?php

namespace App\Entity;

use App\Repository\CommandeRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numero_commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?ClientEcbi $code_client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_commande = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Proforma $code_proforma = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $id_sys = null;

    /**
     * @var Collection<int, FichierCommande>
     */
    #[ORM\OneToMany(targetEntity: FichierCommande::class, mappedBy: 'code_commande')]
    private Collection $fichierCommandes;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;

    /**
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(targetEntity: LigneCommande::class, mappedBy: 'code_commande')]
    private Collection $ligneCommandes;

    public function __construct()
    {
        $this->fichierCommandes = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(string $numero_commande): static
    {
        $this->numero_commande = $numero_commande;

        return $this;
    }

    public function getCodeClient(): ?ClientEcbi
    {
        return $this->code_client;
    }

    public function setCodeClient(?ClientEcbi $code_client): static
    {
        $this->code_client = $code_client;

        return $this;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTime $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getCodeProforma(): ?Proforma
    {
        return $this->code_proforma;
    }

    public function setCodeProforma(?Proforma $code_proforma): static
    {
        $this->code_proforma = $code_proforma;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    public function setCreatedBy(string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection<int, FichierCommande>
     */
    public function getFichierCommandes(): Collection
    {
        return $this->fichierCommandes;
    }

    public function addFichierCommande(FichierCommande $fichierCommande): static
    {
        if (!$this->fichierCommandes->contains($fichierCommande)) {
            $this->fichierCommandes->add($fichierCommande);
            $fichierCommande->setCodeCommande($this);
        }

        return $this;
    }

    public function removeFichierCommande(FichierCommande $fichierCommande): static
    {
        if ($this->fichierCommandes->removeElement($fichierCommande)) {
            // set the owning side to null (unless already changed)
            if ($fichierCommande->getCodeCommande() === $this) {
                $fichierCommande->setCodeCommande(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdSys(): ?string
    {
        return $this->id_sys;
    }

    /**
     * @param string|null $id_sys
     */
    public function setIdSys(?string $id_sys): void
    {
        $this->id_sys = $id_sys;
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
            $ligneCommande->setCodeCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): static
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getCodeCommande() === $this) {
                $ligneCommande->setCodeCommande(null);
            }
        }

        return $this;
    }
}
