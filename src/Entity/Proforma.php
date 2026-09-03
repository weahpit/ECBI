<?php

namespace App\Entity;

use App\Repository\ProformaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProformaRepository::class)]
class Proforma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numero_proforma = null;

    #[ORM\ManyToOne(inversedBy: 'proformas')]
    private ?ClientEcbi $code_client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_proforma = null;

    /**
     * @var Collection<int, LigneProforma>
     */
    #[ORM\OneToMany(targetEntity: LigneProforma::class, mappedBy: 'code_proforma')]
    private Collection $ligneProformas;

    #[ORM\Column(nullable: true)]
    private ?float $total_ht = null;

    #[ORM\Column(nullable: true)]
    private ?float $tva = null;

    #[ORM\Column(nullable: true)]
    private ?float $remise = null;

    #[ORM\Column(nullable: true)]
    private ?float $net_a_payer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $conditions = null;


    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_livraison = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $id_sys = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $montant_lettre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;

    #[ORM\Column(nullable: true)]
    private ?int $taux_remise = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree_validite = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'code_proforma')]
    private Collection $commandes;

    #[ORM\Column(nullable: true)]
    private ?bool $rejet = null;

    public function __construct()
    {
        $this->ligneProformas = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroProforma(): ?string
    {
        return $this->numero_proforma;
    }

    public function setNumeroProforma(string $numero_proforma): static
    {
        $this->numero_proforma = $numero_proforma;

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

    public function getDateProforma(): ?\DateTime
    {
        return $this->date_proforma;
    }

    public function setDateProforma(\DateTime $date_proforma): static
    {
        $this->date_proforma = $date_proforma;

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
            $ligneProforma->setCodeProforma($this);
        }

        return $this;
    }

    public function removeLigneProforma(LigneProforma $ligneProforma): static
    {
        if ($this->ligneProformas->removeElement($ligneProforma)) {
            // set the owning side to null (unless already changed)
            if ($ligneProforma->getCodeProforma() === $this) {
                $ligneProforma->setCodeProforma(null);
            }
        }

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->total_ht;
    }

    public function setTotalHt(?float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getNetAPayer(): ?float
    {
        return $this->net_a_payer;
    }

    public function setNetAPayer(?float $net_a_payer): static
    {
        $this->net_a_payer = $net_a_payer;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(?\DateTime $date_livraison): static
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    public function setCreatedBy(?string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getIdSys(): ?string
    {
        return $this->id_sys;
    }

    public function setIdSys(?string $id_sys): static
    {
        $this->id_sys = $id_sys;

        return $this;
    }

    public function getMontantLettre(): ?string
    {
        return $this->montant_lettre;
    }

    public function setMontantLettre(?string $montant_lettre): static
    {
        $this->montant_lettre = $montant_lettre;

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

    public function getTauxRemise(): ?int
    {
        return $this->taux_remise;
    }

    public function setTauxRemise(?int $taux_remise): static
    {
        $this->taux_remise = $taux_remise;

        return $this;
    }

    public function getDureeValidite(): ?int
    {
        return $this->duree_validite;
    }

    public function setDureeValidite(?int $duree_validite): static
    {
        $this->duree_validite = $duree_validite;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setCodeProforma($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getCodeProforma() === $this) {
                $commande->setCodeProforma(null);
            }
        }

        return $this;
    }

    public function isRejet(): ?bool
    {
        return $this->rejet;
    }

    public function setRejet(?bool $rejet): static
    {
        $this->rejet = $rejet;

        return $this;
    }
}
