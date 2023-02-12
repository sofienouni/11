<?php

namespace App\Entity;


use phpDocumentor\Reflection\Types\String_;

class BiensearchParams
{

    private ?string $type = null;

    private ?array $typeBien = [];

    private ?array $ville = [];

    private ?string $prix = null;

    private ?array $pieces = [];

    private ?string $surface = null;

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

    public function getPieces(): ?array
    {
        $pieces = $this->pieces;
        return array_unique($pieces);
    }

    public function setPieces(?array $pieces): self
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
