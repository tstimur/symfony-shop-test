<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ValidTaxNumber extends Constraint
{
    public string $message = 'Invalid tax number format: "{{ value }}"';
}