<?php

declare(strict_types=1);

namespace App\Service;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

final class PaypalProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $processor,
    ) {
    }

    public function process(string $amount): void
    {
        $priceInCents = (int) bcmul($amount, '100', 0);

        $this
            ->processor
            ->pay($priceInCents);
    }
}
