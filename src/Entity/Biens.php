<?php

namespace App\Entity;

use App\Repository\BiensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiensRepository::class)]
class Biens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pieces = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $etage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chauffage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $climatisation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ascenceur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $concierge = null;

    #[ORM\Column(nullable: true)]
    private ?bool $gardien = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cideosurveillance = null;

    #[ORM\Column(nullable: true)]
    private ?bool $maisongardien = null;

    #[ORM\Column(nullable: true)]
    private ?bool $eclairageexterieur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?bool $type = null;

    #[ORM\Column(nullable: true)]
    private ?bool $neuf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeBien = null;

    #[ORM\ManyToOne]
    private ?Villes $ville = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'bien', targetEntity: Images::class,  cascade:["remove"] )]
    private Collection $images;

    #[ORM\Column(nullable: true)]
    private ?int $surface = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ref = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPieces(): ?string
    {
        return $this->pieces;
    }

    public function setPieces(?string $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(?string $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function isChauffage(): ?bool
    {
        return $this->chauffage;
    }

    public function setChauffage(?bool $chauffage): self
    {
        $this->chauffage = $chauffage;

        return $this;
    }

    public function isClimatisation(): ?bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(?bool $climatisation): self
    {
        $this->climatisation = $climatisation;

        return $this;
    }

    public function isAscenceur(): ?bool
    {
        return $this->ascenceur;
    }

    public function setAscenceur(?bool $ascenceur): self
    {
        $this->ascenceur = $ascenceur;

        return $this;
    }

    public function isConcierge(): ?bool
    {
        return $this->concierge;
    }

    public function setConcierge(?bool $concierge): self
    {
        $this->concierge = $concierge;

        return $this;
    }

    public function isGardien(): ?bool
    {
        return $this->gardien;
    }

    public function setGardien(?bool $gardien): self
    {
        $this->gardien = $gardien;

        return $this;
    }

    public function isCideosurveillance(): ?bool
    {
        return $this->cideosurveillance;
    }

    public function setCideosurveillance(?bool $cideosurveillance): self
    {
        $this->cideosurveillance = $cideosurveillance;

        return $this;
    }

    public function isMaisongardien(): ?bool
    {
        return $this->maisongardien;
    }

    public function setMaisongardien(?bool $maisongardien): self
    {
        $this->maisongardien = $maisongardien;

        return $this;
    }

    public function isEclairageexterieur(): ?bool
    {
        return $this->eclairageexterieur;
    }

    public function setEclairageexterieur(?bool $eclairageexterieur): self
    {
        $this->eclairageexterieur = $eclairageexterieur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function isType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isNeuf(): ?bool
    {
        return $this->neuf;
    }

    public function setNeuf(?bool $neuf): self
    {
        $this->neuf = $neuf;

        return $this;
    }

    public function getTypeBien(): ?string
    {
        return $this->typeBien;
    }

    public function setTypeBien(?string $typeBien): self
    {
        $this->typeBien = $typeBien;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setBien($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBien() === $this) {
                $image->setBien(null);
            }
        }

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

}
