<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessorException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

final class StripeProcessorAdapter implements PaymentProcessorInterface
{
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
}
