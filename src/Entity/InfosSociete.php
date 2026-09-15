<?php

namespace App\Entity;

use App\Repository\InfosSocieteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosSocieteRepository::class)]
class InfosSociete
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $raison_sociale = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sigle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $mobile = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cc = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $rccim = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type_imposition = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $centre_impots = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secteur_activite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteweb = null;

    #[ORM\ManyToOne(inversedBy: 'infosSocietes')]
    private ?Pays $code_pays = null;

    #[ORM\ManyToOne(inversedBy: 'infosSocietes')]
    private ?Ville $code_ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raison_sociale;
    }

    public function setRaisonSociale(string $raison_sociale): static
    {
        $this->raison_sociale = $raison_sociale;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): static
    {
        $this->sigle = $sigle;

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

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): static
    {
        $this->cc = $cc;

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

    public function getRccim(): ?string
    {
        return $this->rccim;
    }

    public function setRccim(?string $rccim): static
    {
        $this->rccim = $rccim;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

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

    public function getTypeImposition(): ?string
    {
        return $this->type_imposition;
    }

    public function setTypeImposition(?string $type_imposition): static
    {
        $this->type_imposition = $type_imposition;

        return $this;
    }

    public function getCentreImpots(): ?string
    {
        return $this->centre_impots;
    }

    public function setCentreImpots(?string $centre_impots): static
    {
        $this->centre_impots = $centre_impots;

        return $this;
    }

    public function getSecteurActivite(): ?string
    {
        return $this->secteur_activite;
    }

    public function setSecteurActivite(?string $secteur_activite): static
    {
        $this->secteur_activite = $secteur_activite;

        return $this;
    }

    public function getSiteweb(): ?string
    {
        return $this->siteweb;
    }

    public function setSiteweb(?string $siteweb): static
    {
        $this->siteweb = $siteweb;

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

    public function getCodeVille(): ?Ville
    {
        return $this->code_ville;
    }

    public function setCodeVille(?Ville $code_ville): static
    {
        $this->code_ville = $code_ville;

        return $this;
    }
}
