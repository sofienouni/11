<?php

namespace App\Entity;


use phpDocumentor\Reflection\Types\String_;

class BiensearchParams
{

    private ?string $type = null;

    private ?string $typeBien = null;

    private ?string $ville = null;

    private ?string $prix = null;

    private ?string $pieces = null;

    private ?string $surface = null;

    private ?string $neuf = null;

    private ?string $ref = null;


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;

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

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(?string $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getNeuf(): ?string
    {
        return $this->neuf;
    }

    public function setNeuf(?string $neuf): self
    {
        $this->neuf = $neuf;

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
