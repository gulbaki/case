<?php


namespace App\Service\Discount\Factory;

use App\Service\Discount\Rule\Over1000DiscountRule;
use App\Service\Discount\Rule\Buy6GetOneFreeRule;
use App\Service\Discount\Rule\TwoOrMoreCategoryDiscountRule;
use App\Entity\DiscountRule as DiscountRuleEntity;
use App\Service\Discount\DiscountRuleInterface;
use RuntimeException;
use ReflectionClass;


class DiscountRuleFactory
{
    /**
     * A map of discount rule codes to their respective classes.
     */
    private const RULE_CLASS_MAP = [
        '10_PERCENT_OVER_1000' => Over1000DiscountRule::class,
        'BUY_6_GET_1'          => Buy6GetOneFreeRule::class,
        'CHEAPEST_IN_CATEGORY' => TwoOrMoreCategoryDiscountRule::class,
    ];

    /**
     * Creates a DiscountRule object from the given entity.
     *
     * @throws RuntimeException
     */
    public function createRule(DiscountRuleEntity $entity): DiscountRuleInterface
    {
        $ruleCode = $entity->getRuleCode();
        $params   = $entity->getParameters() ?? [];

        if (!array_key_exists($ruleCode, array: self::RULE_CLASS_MAP)) {
            throw new RuntimeException("Unknown discount rule code: $ruleCode");
        }

        $fqcn = self::RULE_CLASS_MAP[$ruleCode];

        return $this->instantiateRule($fqcn, $params);
    }

    /**
     * Instantiate the rule class using reflection to match constructor parameter names.
     */
    private function instantiateRule(string $fqcn, array $params = []): DiscountRuleInterface
    {
        $reflection = new ReflectionClass($fqcn);

        // If no constructor or constructor has no parameters,
        // we can instantiate with `newInstance()` directly.
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            // Class has no constructor or no args needed
            return $reflection->newInstance();
        }

        // Map $params array to constructor parameter names
        $constructorParams = $constructor->getParameters();
        $args             = [];

        foreach ($constructorParams as $param) {
            $paramName = $param->getName();

            if (array_key_exists($paramName, $params)) {
                // Use the value from $params array
                $args[] = $params[$paramName];
            } elseif ($param->isDefaultValueAvailable()) {
                // Use default value from the constructor definition
                $args[] = $param->getDefaultValue();
            } else {
                throw new RuntimeException(
                    "Missing required constructor param '$paramName' for class '$fqcn'"
                );
            }
        }

        // Instantiate the rule using the matched arguments
        $instance = $reflection->newInstanceArgs($args);

        if (!$instance instanceof DiscountRuleInterface) {
            throw new RuntimeException("Class '$fqcn' does not implement DiscountRuleInterface");
        }

        return $instance;
    }
}
