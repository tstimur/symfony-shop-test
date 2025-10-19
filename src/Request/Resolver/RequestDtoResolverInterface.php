<?php

declare(strict_types=1);

namespace App\src\Request\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface RequestDtoResolverInterface
{
    public function resolve(Request $request, string $dtoClass): iterable;
}
