<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidTaxNumber extends Constraint
{
    public string $message = 'Invalid tax number format: "{{ value }}"';
}
