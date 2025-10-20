<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\PaymentProcessorException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class ApiExceptionListener
{
    /**
     * @throws \ReflectionException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = match (true) {
            $exception instanceof BadRequestHttpException || $exception instanceof PaymentProcessorException => Response::HTTP_BAD_REQUEST,
            $exception instanceof NotFoundHttpException => Response::HTTP_NOT_FOUND,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $response = new JsonResponse(
            [
                'error' => [
                    'type' => (new \ReflectionClass($exception))->getShortName(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ],
            ], $statusCode
        );

        $event->setResponse($response);
    }
}
