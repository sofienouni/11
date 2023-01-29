<?php

namespace App\Entity;


use phpDocumentor\Reflection\Types\String_;

class BiensearchParams
{

    private ?string $type = null;

    private ?array $typeBien = [];

    private ?array $ville = [];

    private ?string $prix = null;

    private ?string $pieces = null;

    private ?string $surface = null;

    private ?string $neuf = null;

    private ?string $ref = null;

    private ?string $trie = null;



    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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
    public function getTrie(): ?string
    {
        return $this->trie;
    }

    public function setTrie(?string $trie): self
    {
        $this->trie = $trie;

        return $this;
    }

}
