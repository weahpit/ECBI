<?php

namespace App\Entity;

use App\Repository\ColorAppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorAppRepository::class)]
class ColorApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $background = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $textColor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Customize>
     */
    #[ORM\OneToMany(targetEntity: Customize::class, mappedBy: 'navbar_font')]
    private Collection $customizes;

    public function __construct()
    {
        $this->customizes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): static
    {
        $this->background = $background;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): static
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Customize>
     */
    public function getCustomizes(): Collection
    {
        return $this->customizes;
    }

    public function addCustomize(Customize $customize): static
    {
        if (!$this->customizes->contains($customize)) {
            $this->customizes->add($customize);
            $customize->setNavbarFont($this);
        }

        return $this;
    }

    public function removeCustomize(Customize $customize): static
    {
        if ($this->customizes->removeElement($customize)) {
            // set the owning side to null (unless already changed)
            if ($customize->getNavbarFont() === $this) {
                $customize->setNavbarFont(null);
            }
        }

        return $this;
    }
}
