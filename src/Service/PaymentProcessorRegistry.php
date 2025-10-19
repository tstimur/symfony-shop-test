<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\PaymentProcessorException;

class PaymentProcessorRegistry
{
    private array $processors = [];

    public function register(
        string $name,
        PaymentProcessorInterface $processor,
    ): void {
        $this->processors[$name] = $processor;
    }

    /**
     * @throws PaymentProcessorException
     */
    public function get(string $name): PaymentProcessorInterface
    {
        if (!isset($this->processors[$name])) {
            throw new PaymentProcessorException('Processor "'.$name.'" not found');
        }

        return $this->processors[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->processors[$name]);
    }

    public function getAll(): array
    {
        return array_keys($this->processors);
    }
}
