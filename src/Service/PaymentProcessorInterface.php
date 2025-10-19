<?php

declare(strict_types=1);

namespace App\Service;

interface PaymentProcessorInterface
{
    /**
     * @param string $amount Amount in currency (e.g., "124.00")
     *
     * @throws \Exception if payment fails
     */
    public function process(string $amount): void;
}
