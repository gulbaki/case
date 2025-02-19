<?php

namespace App\Service\Discount;

abstract class AbstractDiscountRule implements DiscountRuleInterface
{
    protected int $priority = 100; // default priority

    public function getPriority(): int
    {
        return $this->priority;
    }
}
