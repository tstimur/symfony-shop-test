<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\PaymentProcessorException;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ApiExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {}

    /**
     * @throws ReflectionException
     */
    #[AsEventListener]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $this
            ->logger
            ->error(
                $exception->getMessage(),
                [
                    'exception' => $exception,
                    'trace' => $exception->getTraceAsString(),
                ]
            );

        $statusCode = match (true) {
            $exception instanceof BadRequestHttpException || $exception instanceof PaymentProcessorException => Response::HTTP_BAD_REQUEST,
            $exception instanceof NotFoundHttpException => Response::HTTP_NOT_FOUND,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $response = new JsonResponse(
            [
                'error' => [
                    'type' => (new ReflectionClass($exception))->getShortName(),
                    'message' => $exception->getMessage(),
                    ],
                ], $statusCode
        );

        $event->setResponse($response);
    }
}
