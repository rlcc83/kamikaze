<?php

namespace App\Entity;

use App\Repository\LeadRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeadRepository::class)]
class Lead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    #[ORM\Column(type: 'datetime')]
    private $dateEnd;

    #[ORM\Column(type: 'integer')]
    private $profit;

    public function __toString()
    {
        return $this->name;
    }

    public static function create(
        string $name,
        DateTimeInterface $dateStart,
        DateTimeInterface $dateEnd,
        int $profit,
    ) {
        $lead = new self();

        $lead->setName($name);
        $lead->setDateStart($dateStart);
        $lead->setDateEnd($dateEnd);
        $lead->setProfit($profit);

        return $lead;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getProfit(): int
    {
        return $this->profit;
    }

    public function setProfit(int $profit): self
    {
        $this->profit = $profit;

        return $this;
    }
}
