<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.payment_processor')]
interface PaymentProcessorInterface
{
    /**
     * @param string $amount Amount in currency (e.g., "124.00")
     *
     * @throws \Exception if payment fails
     */
    public function process(string $amount): void;

    public function supports(string $method): bool;
}
