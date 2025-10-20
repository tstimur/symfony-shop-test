<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TaxNumber extends Constraint
{
    public string $message = 'Invalid tax number format: "{{ value }}"';

    public function validatedBy(): string
    {
        return TaxNumberValidator::class;
    }
}
