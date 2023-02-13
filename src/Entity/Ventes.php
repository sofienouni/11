<?php

namespace App\Entity;

use App\Repository\VentesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VentesRepository::class)]
class Ventes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/[0-9]{8}/',
        message: 'Your name cannot contain a number'
    )]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $treated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $operation = null;

    #[ORM\ManyToOne]
    private ?TypeBien $typebien = null;

    #[ORM\ManyToOne]
    private ?Villes $ville = null;

    #[ORM\OneToMany(mappedBy: 'vente', targetEntity: ClientImages::class,  cascade:["remove"] )]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }


    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTreated(): ?\DateTimeInterface
    {
        return $this->treated;
    }

    public function setTreated(?\DateTimeInterface $treated): self
    {
        $this->treated = $treated;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(?string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getTypebien(): ?TypeBien
    {
        return $this->typebien;
    }

    public function setTypebien(?TypeBien $typebien): self
    {
        $this->typebien = $typebien;

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

    /**
     * @return Collection<int, ClientImages>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ClientImages $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVente($this);
        }

        return $this;
    }

    public function removeImage(ClientImages $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVente() === $this) {
                $image->setVente(null);
            }
        }

        return $this;
    }
}
