<?php

namespace App\Service\Discount\Chain;

use App\Service\Discount\Factory\DiscountRuleFactory;
use App\Repository\DiscountRuleRepository;


class DiscountRuleChain
{
    private array $rules = [];

    public function __construct(
        private DiscountRuleRepository $discountRuleRepository,
        private DiscountRuleFactory $factory
    ) {
        $this->loadRules();
    }

    /**
     * Loads active rules from DB, instantiates them with relevant parameters.
     */
    private function loadRules(): void
    {
        // Query all active rules, ordered by priority ascending
        $entities = $this->discountRuleRepository->findBy(
            ['active' => true],
            ['priority' => 'ASC']
        );

        foreach ($entities as $entity) {
            $this->rules[] = $this->factory->createRule($entity);
        }
    }

    /**
     * @return DiscountRuleInterface[] 
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
