<?php

declare(strict_types=1);

namespace App\Exception;

final class PaymentProcessorException extends \Exception
{
    public function __construct(
        string $message,
        public readonly string $processor = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, previous: $message);
    }
}
