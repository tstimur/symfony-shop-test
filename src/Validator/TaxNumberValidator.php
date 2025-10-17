<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class TaxNumberValidator extends ConstraintValidator
{
    private const array TAX_PATTERNS = [
        'DE' => '/^DE\d{9}$/',
        'IT' => '/^IT\d{11}$/',
        'GR' => '/^GR\d{9}$/',
        'FR' => '/^FR[A-Z]{2}\d{9}$/',
    ];

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidTaxNumber) {
            throw new UnexpectedTypeException($constraint, ValidTaxNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $countryCode = substr($value, 0, 2);

        if (!isset(self::TAX_PATTERNS[$countryCode])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
