<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TaxRate;
use App\Repository\TaxRateRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Country;

class TaxService
{
    public function __construct(
        private TaxRateRepository $taxRateRepository
    ) {}

    public function getCountryFromTaxNumber(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }

    public function getTaxRate(
        string $countryCode,
        ?DateTime $date = null
    ): TaxRate
    {
        $date = $date ?? new DateTime();

        $taxRate = $this
            ->taxRateRepository
            ->findByCountryCodeAndDate($countryCode, $date);

        if (null === $taxRate) {
            throw new BadRequestHttpException(
                "Tax rate not found for country: {$countryCode}"
            );
        }

        return $taxRate;
    }

    public function calculateTax(
        string $taxNumber,
        string $basePrice
    ): string
    {
        $countryCode = $this->getCountryFromTaxNumber($taxNumber);
        $taxRate = $this->getTaxRate($countryCode)->getRate();
        $rateAsDecimal = bcdiv($taxRate, '100', 4);
        return bcmul($basePrice, $rateAsDecimal, 2);
    }
}