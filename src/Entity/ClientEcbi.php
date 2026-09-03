<?php

namespace App\Entity;

use App\Repository\CLientRepository;
use App\Repository\ClienEcbiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: ClienEcbiRepository::class)]
class ClientEcbi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $rsClient = null;

    #[ORM\Column(length: 255)]
    private ?string $sigle = null;

    #[ORM\ManyToOne(inversedBy: 'cLients')]
    private ?TypeClient $typeClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personne_ressource = null;
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cc = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pays = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'code_client')]
    private Collection $commandes;

    /**
     * @var Collection<int, Proforma>
     */
    #[ORM\OneToMany(targetEntity: Proforma::class, mappedBy: 'code_client')]
    private Collection $proformas;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $rccim = null;

    #[ORM\ManyToOne(inversedBy: 'clientEcbis')]
    private ?Pays $code_pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bp = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->proformas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getRsClient(): ?string
    {
        return $this->rsClient;
    }

    public function setRsClient(string $rsClient): static
    {
        $this->rsClient = $rsClient;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getTypeClient(): ?TypeClient
    {
        return $this->typeClient;
    }

    public function setTypeClient(?TypeClient $typeClient): static
    {
        $this->typeClient = $typeClient;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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

    public function getPersonneRessource(): ?string
    {
        return $this->personne_ressource;
    }

    public function setPersonneRessource(?string $personne_ressource): static
    {
        $this->personne_ressource = $personne_ressource;

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
            $commande->setCodeClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getCodeClient() === $this) {
                $commande->setCodeClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Proforma>
     */
    public function getProformas(): Collection
    {
        return $this->proformas;
    }

    public function addProforma(Proforma $proforma): static
    {
        if (!$this->proformas->contains($proforma)) {
            $this->proformas->add($proforma);
            $proforma->setCodeClient($this);
        }

        return $this;
    }

    public function removeProforma(Proforma $proforma): static
    {
        if ($this->proformas->removeElement($proforma)) {
            // set the owning side to null (unless already changed)
            if ($proforma->getCodeClient() === $this) {
                $proforma->setCodeClient(null);
            }
        }

        return $this;
    }
    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): static
    {
        $this->cc = $cc;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getRccim(): ?string
    {
        return $this->rccim;
    }

    public function setRccim(?string $rccim): static
    {
        $this->rccim = $rccim;

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

    public function getBp(): ?string
    {
        return $this->bp;
    }

    public function setBp(?string $bp): static
    {
        $this->bp = $bp;

        return $this;
    }
}
