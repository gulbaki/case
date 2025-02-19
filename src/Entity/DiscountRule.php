<?php

namespace App\Entity;

use App\Repository\DiscountRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscountRuleRepository::class)]
#[ORM\Table(name: 'discount_rules')]
class DiscountRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // A unique identifier or code, e.g. "10_PERCENT_OVER_1000"
    #[ORM\Column(type: 'string', length: 50)]
    private string $ruleCode;

    // JSON or string-based parameters to store thresholds, categories, discount rates
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $parameters = [];

    // Priority for the chain (lowest number = highest priority, or vice versa)
    #[ORM\Column(type: 'integer')]
    private int $priority;

    // This could be an "active" flag or other toggles
    #[ORM\Column(type: 'boolean')]
    private bool $active = true;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRuleCode(): ?string
    {
        return $this->ruleCode;
    }

    public function setRuleCode(string $ruleCode): static
    {
        $this->ruleCode = $ruleCode;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(?array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
