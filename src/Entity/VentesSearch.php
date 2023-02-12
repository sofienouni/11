<?php

namespace App\Entity;


use phpDocumentor\Reflection\Types\String_;

class VentesSearch
{

    private ?string $nom = null;

    private ?string $prenom = null;

    private ?string $telephone = null;

    private ?string $ref = null;

    private ?array $ville = [];

    private ?array $typeBien = [];


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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getVille(): ?array
    {

        $ville = $this->ville;
        return array_unique($ville);
    }

    public function setVille(?array $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTypeBien(): ?array
    {
        $typeBien = $this->typeBien;
        return array_unique($typeBien);
    }

    public function setTypeBien(?array $typeBien): self
    {
        $this->typeBien = $typeBien;

        return $this;
    }
}
