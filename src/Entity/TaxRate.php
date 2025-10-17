<?php

namespace App\Entity;

use App\Repository\TaxRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRateRepository::class)]
class TaxRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $rate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $effectiveFrom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $effectiveUntil = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->countryCode;
    }

    public function setCountry(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getEffectiveFrom(): ?\DateTimeImmutable
    {
        return $this->effectiveFrom;
    }

    public function setEffectiveFrom(\DateTimeImmutable $effectiveFrom): static
    {
        $this->effectiveFrom = $effectiveFrom;

        return $this;
    }

    public function getEffectiveUntil(): ?\DateTimeImmutable
    {
        return $this->effectiveUntil;
    }

    public function setEffectiveUntil(?\DateTimeImmutable $effectiveUntil): static
    {
        $this->effectiveUntil = $effectiveUntil;

        return $this;
    }
}
