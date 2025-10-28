<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PriceCalculatorService
{
    private const string DISCOUNT_ZERO = '0.00';

    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxService $taxService,
    ) {
    }

    public function calculatePrice(
        int $productId,
        string $taxNumber,
        ?string $couponCode,
    ): array {
        $product = $this
            ->productRepository
            ->find($productId);

        if (null === $product) {
            throw new NotFoundHttpException('Product not found');
        }

        $basePrice = $product->getPrice();

        $discount = self::DISCOUNT_ZERO;
        if (null !== $couponCode) {
            $coupon = $this
                ->couponRepository
                ->findOneBy(['code' => $couponCode]);

            if (null === $coupon) {
                throw new NotFoundHttpException('Invalid coupon code');
            }
            $discount = $this->calculateDiscount($basePrice, $coupon);
        }

        $priceAfterDiscount = bcsub($basePrice, $discount, 2);

        $countryCode = $this->taxService->getCountryFromTaxNumber($taxNumber);
        $tax = $this->taxService->calculateTax($countryCode, $priceAfterDiscount);

        $total = bcadd($priceAfterDiscount, $tax, 2);

        return [
            'basePrice' => $basePrice,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    public function calculateDiscount(string $basePrice, Coupon $coupon): string
    {
        return match ($coupon->getType()) {
            CouponType::FIXED => $this->calculateFixedDiscount($basePrice, $coupon->getValue()),
            CouponType::PERCENT => $this->calculatePercentDiscount($basePrice, $coupon->getValue()),
            default => self::DISCOUNT_ZERO,
        };
    }

    private function calculateFixedDiscount(string $basePrice, string $value): string
    {
        return bccomp($value, $basePrice, 2) <= 0 ? $value : $basePrice;
    }

    private function calculatePercentDiscount(string $basePrice, string $value): string
    {
        return bcmul($basePrice, bcdiv($value, '100', 4), 2);
    }
}
