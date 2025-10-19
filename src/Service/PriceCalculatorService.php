<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PriceCalculatorService
{
    private const string DISCOUNT_ZERO = '0.00';

    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxService $taxService,
    ) {}

    public function calculatePrice(
        int $productId,
        string $taxNumber,
        ?string $couponCode,
    ): array {
        $product = $this
            ->productRepository
            ->find($productId);

        if ($product === null) {
            throw new NotFoundHttpException('Product not found');
        }

        $basePrice = $product->getPrice();

        $discount = '0.00';
        if ($couponCode !== null) {
            $coupon = $this
                ->couponRepository
                ->findOneBy(['code' => $couponCode]);

            if ($coupon === null) {
                throw new NotFoundHttpException('Invalid coupon code');
            }
            $discount = $this->calculateDiscount($basePrice, $coupon);
        }

        $priceAfterDiscount = bcsub($basePrice, $discount, 2);

        $countryCode = $this->taxService->getCountryFromTaxNumber($taxNumber);
        $tax = $this->taxService->calculateTax($priceAfterDiscount, $countryCode);

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
        if ($coupon->getFixedDiscount() !== null) {
            $fixedDiscount = $coupon->getFixedDiscount();
            return $fixedDiscount <= $basePrice ? $fixedDiscount : $basePrice;
        }

        if ($coupon->getPercentDiscount() !== null) {
            return bcmul(
                $basePrice,
                bcdiv($coupon->getPercentDiscount(), '100', 4),
                2
            );
        }

        return self::DISCOUNT_ZERO;
    }
}