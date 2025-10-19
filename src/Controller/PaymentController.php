<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\DTO\PriceCalculationRequestDTO;
use App\Request\DTO\PurchaseRequestDTO;
use App\Service\PaymentProcessorRegistry;
use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->json([]);
    }

    #[Route('/purchase', methods: ['POST'])]
    public function purchase(PurchaseRequestDTO $dto): JsonResponse
    {
        return $this->json([]);
    }
}
