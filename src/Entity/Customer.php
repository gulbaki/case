<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\Table(name: 'customers')]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 150)]
    private string $name;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $since;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $revenue = '0.00';

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSince(): ?\DateTimeInterface
    {
        return $this->since;
    }

    public function setSince(\DateTimeInterface $since): static
    {
        $this->since = $since;

        return $this;
    }

    public function getRevenue(): ?string
    {
        return $this->revenue;
    }

    public function setRevenue(string $revenue): static
    {
        $this->revenue = $revenue;

        return $this;
    }

    
}
