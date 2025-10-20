<?php

declare(strict_types=1);

namespace App\Service;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

final readonly class PaypalProcessorAdapter implements PaymentProcessorInterface
{
    public const string NAME = 'paypal';

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

    public function supports(string $method): bool
    {
        return self::NAME === $method;
    }
}
