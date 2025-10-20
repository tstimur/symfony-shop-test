<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessorException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class PaymentProcessorRegistry
{
    /**
     * @param iterable<PaymentProcessorInterface> $processors
     */
    public function __construct(
        #[TaggedIterator('app.payment_processor')]
        private readonly iterable $processors)
    {
    }

    /**
     * @throws PaymentProcessorException
     */
    public function get(string $method): PaymentProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supports($method)) {
                return $processor;
            }
        }

        throw new PaymentProcessorException('Processor "'.$method.'" not found');
    }
}
