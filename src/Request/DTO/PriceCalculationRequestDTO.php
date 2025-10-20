<?php

declare(strict_types=1);

namespace App\Request\DTO;

use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

final class PriceCalculationRequestDTO
{
    #[Assert\NotBlank(message: 'Product ID is required')]
    #[Assert\Type('integer', message: 'Product ID must be an integer')]
    #[Assert\Positive(message: 'Product ID must be positive')]
    public ?int $product = null;

    #[Assert\NotBlank(message: 'Tax number is required')]
    #[Assert\Type('string', message: 'Tax number must be a string')]
    #[TaxNumber]
    public ?string $taxNumber = null;

    #[Assert\Type('string', message: 'Coupon code must be a string')]
    public ?string $couponCode = null;
}
