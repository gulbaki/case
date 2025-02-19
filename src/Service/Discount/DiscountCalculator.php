<?php

namespace App\Service\Discount;

use App\Service\Discount\Chain\DiscountRuleChain;

use App\Entity\Order;

class DiscountCalculator
{
    private DiscountRuleChain $ruleChain;

    public function __construct(DiscountRuleChain $ruleChain)
    {
        $this->ruleChain = $ruleChain;
    }

    public function calculate(Order $order): array
    {
        $originalTotal = (float) $order->getTotal();
        $currentSubtotal = $originalTotal;
        $appliedDiscounts = [];

        foreach ($this->ruleChain->getRules() as $rule) {
            if ($rule->isApplicable($order)) {
                $result = $rule->apply($order, $currentSubtotal);
                if ($result !== null) {
                    $appliedDiscounts[] = [
                        'discountReason' => $result['discountReason'],
                        'discountAmount' => number_format($result['discountAmount'], 2, '.', ''),
                        'subtotal' => number_format($result['subtotal'], 2, '.', '')
                    ];
                    $currentSubtotal = $result['subtotal'];
                }
            }
        }

        $totalDiscount = $originalTotal - $currentSubtotal;

        return [
            'orderId'         => $order->getId(),
            'discounts'       => $appliedDiscounts,
            'totalDiscount'   => number_format($totalDiscount, 2, '.', ''),
            'discountedTotal' => number_format($currentSubtotal, 2, '.', ''),
        ];
    }
}
