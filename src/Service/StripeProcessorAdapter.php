<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessorException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

final readonly class StripeProcessorAdapter implements PaymentProcessorInterface
{
    public const string NAME = 'stripe';

    public function __construct(
        private StripePaymentProcessor $processor,
    ) {
    }

    public function process(string $amount): void
    {
        $price = (float) $amount;

        $success = $this
            ->processor
            ->processPayment($price);

        if (false === $success) {
            throw new PaymentProcessorException('Failed to process payment', StripePaymentProcessor::class);
        }
    }

    public function supports(string $method): bool
    {
        return self::NAME === $method;
    }
}
