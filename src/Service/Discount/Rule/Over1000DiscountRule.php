<?php

namespace App\Service\Discount\Rule;
use App\Service\Discount\AbstractDiscountRule;

use App\Entity\Order;

class Over1000DiscountRule extends AbstractDiscountRule
{
    protected int $priority = 10;

    private float $threshold;
    private float $rate;

    public function __construct(float $threshold = 1000.0, float $rate = 0.10)
    {
        $this->threshold = $threshold;
        $this->rate = $rate;
    }

    public function isApplicable(Order $order): bool
    {
        return (float) $order->getTotal() >= $this->threshold;
    }

    public function apply(Order $order, float $currentSubtotal): ?array
    {
        $discountAmount = $currentSubtotal * $this->rate;
        $newSubtotal = $currentSubtotal - $discountAmount;

        return [
            'discountReason' => '10_PERCENT_OVER_1000',
            'discountAmount' => round($discountAmount, 2),
            'subtotal' => round($newSubtotal, 2),
        ];
    }
}
