<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Filter
{

    private ?int $MaxPrice = null;

    #[Assert\Range(min:10, max:400)]
    private ?int $MinSurface = null;



    public function getMaxPrice(): ?int
    {
        return $this->MaxPrice;
    }

    public function setMaxPrice(?int $MaxPrice): static
    {
        $this->MaxPrice = $MaxPrice;

        return $this;
    }

    public function getMinSurface(): ?int
    {
        return $this->MinSurface;
    }

    public function setMinSurface(?int $MinSurface): static
    {
        $this->MinSurface = $MinSurface;

        return $this;
    }
}
