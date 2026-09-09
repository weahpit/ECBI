<?php

namespace App\Entity;

use App\Repository\CustomizeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomizeRepository::class)]
class Customize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prefix_application = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo_login = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $barre_titre_couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icone_application = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $type_icone_application = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $police = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $favicon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fond_application = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type_fond = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entete_doc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefixApplication(): ?string
    {
        return $this->prefix_application;
    }

    public function setPrefixApplication(?string $prefix_application): static
    {
        $this->prefix_application = $prefix_application;

        return $this;
    }

    public function getLogoLogin(): ?string
    {
        return $this->logo_login;
    }

    public function setLogoLogin(?string $logo_login): static
    {
        $this->logo_login = $logo_login;

        return $this;
    }

    public function getBarreTitreCouleur(): ?string
    {
        return $this->barre_titre_couleur;
    }

    public function setBarreTitreCouleur(?string $barre_titre_couleur): static
    {
        $this->barre_titre_couleur = $barre_titre_couleur;

        return $this;
    }

    public function getIconeApplication(): ?string
    {
        return $this->icone_application;
    }

    public function setIconeApplication(?string $icone_application): static
    {
        $this->icone_application = $icone_application;

        return $this;
    }

    public function getTypeIconeApplication(): ?string
    {
        return $this->type_icone_application;
    }

    public function setTypeIconeApplication(?string $type_icone_application): static
    {
        $this->type_icone_application = $type_icone_application;

        return $this;
    }

    public function getPolice(): ?string
    {
        return $this->police;
    }

    public function setPolice(?string $police): static
    {
        $this->police = $police;

        return $this;
    }

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function setFavicon(?string $favicon): static
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getFondApplication(): ?string
    {
        return $this->fond_application;
    }

    public function setFondApplication(?string $fond_application): static
    {
        $this->fond_application = $fond_application;

        return $this;
    }

    public function getTypeFond(): ?string
    {
        return $this->type_fond;
    }

    public function setTypeFond(?string $type_fond): static
    {
        $this->type_fond = $type_fond;

        return $this;
    }

    public function getEnteteDoc(): ?string
    {
        return $this->entete_doc;
    }

    public function setEnteteDoc(?string $entete_doc): static
    {
        $this->entete_doc = $entete_doc;

        return $this;
    }
}
