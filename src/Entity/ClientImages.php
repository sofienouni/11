<?php

namespace App\Entity;

use App\Repository\ClientImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientImagesRepository::class)]
class ClientImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Ventes $vente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getVente(): ?Ventes
    {
        return $this->vente;
    }

    public function setVente(?Ventes $vente): self
    {
        $this->vente = $vente;

        return $this;
    }
}
