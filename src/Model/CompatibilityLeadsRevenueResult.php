<?php

namespace App\Model;

use App\Interface\CompatibilityLeads;
use App\Repository\LeadRepository;
use App\Entity\Lead;
use App\Interface\CompatibilityLeadsInterface;
use DateTimeInterface;

class CompatibilityLeadsRevenueResult
{
    public function __construct(private array $leads)
    {
    }

    public function getLeads()
    {
        return $this->leads;
    }

    public function totalAmount()
    {
        $totalAmount = 0;

        foreach ($this->leads as $lead) {
            $totalAmount += $lead->getProfit();
        }

        return $totalAmount;
    }
}
