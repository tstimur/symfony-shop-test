<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $fixedDiscount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $percentDiscount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getFixedDiscount(): ?string
    {
        return $this->fixedDiscount;
    }

    public function setFixedDiscount(?string $fixedDiscount): static
    {
        $this->fixedDiscount = $fixedDiscount;

        return $this;
    }

    public function getPercentDiscount(): ?string
    {
        return $this->percentDiscount;
    }

    public function setPercentDiscount(?string $percentDiscount): static
    {
        $this->percentDiscount = $percentDiscount;

        return $this;
    }
}
