<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\PaymentProcessorException;
use App\Request\DTO\PriceCalculationRequestDTO;
use App\Request\DTO\PurchaseRequestDTO;
use App\Service\PaymentProcessorRegistry;
use App\Service\PriceCalculatorService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PaymentController extends AbstractController
{
    public function __construct(
        private PriceCalculatorService $priceCalculatorService,
        private PaymentProcessorRegistry $paymentProcessorRegistry,

    ) {}

    #[Route('/calculate-price', methods: ['POST'])]
    public function calculatePrice(PriceCalculationRequestDTO $dto): JsonResponse
    {
        $priceData = $this
            ->priceCalculatorService
            ->calculatePrice(
                $dto->product,
                $dto->taxNumber,
                $dto->couponCode
            );

        return $this->json([
            'message' => 'Price calculated',
            'price' => $priceData
        ],
            Response::HTTP_OK
        );
    }

    /**
     * @throws PaymentProcessorException
     * @throws Exception
     */
    #[Route('/purchase', methods: ['POST'])]
    public function purchase(PurchaseRequestDTO $dto): JsonResponse
    {
        $priceData = $this->priceCalculatorService->calculatePrice(
            $dto->product,
            $dto->taxNumber,
            $dto->couponCode,
        );

        $processor = $this
            ->paymentProcessorRegistry
            ->get($dto->paymentProcessor);

        $processor->process($priceData['total']);

        return $this->json(
            [
                'message' => 'Purchase successful',
                'total' => $priceData['total'],
                ],
            Response::HTTP_OK
        );
    }
}
