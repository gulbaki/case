<?php

namespace App\Service\Discount\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\Discount\AbstractDiscountRule;


class Buy6GetOneFreeRule extends AbstractDiscountRule
{
    protected int $priority = 20;

    private int $category;

    public function __construct(int $category)
    {
        $this->category = $category;
    }

    public function isApplicable(Order $order): bool
    {
        // Check if any item in the order belongs to the specified category with qty >= 6
        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === $this->category
                && $item->getQuantity() >= 6
            ) {
                return true;
            }
        }
        return false;
    }

    public function apply(Order $order, float $currentSubtotal): ?array
    {
        // For simplicity, apply "one free unit" to the first matching item
        // If multiple items match, you might handle them all or pick the best scenario
        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === $this->category
                && $item->getQuantity() >= 6
            ) {
                // The discount is the cost of 1 unit of that item
                $unitPrice = (float) $item->getUnitPrice();
                $discountAmount = $unitPrice;
                $newSubtotal = $currentSubtotal - $discountAmount;

                return [
                    'discountReason' => 'BUY_6_GET_1',
                    'discountAmount' => round($discountAmount, 2),
                    'subtotal' => round($newSubtotal, 2),
                ];
            }
        }

        return null;
    }
}
