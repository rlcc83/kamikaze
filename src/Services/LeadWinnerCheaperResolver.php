<?php

namespace App\Services;

use App\Interface\LeadWinnerResolverInterface;
use App\Model\CompatibilityLeadsRevenueResult;

class LeadWinnerCheaperResolver implements LeadWinnerResolverInterface
{
    public function resolve(array $leads): CompatibilityLeadsRevenueResult
    {
        $leadExpensive = new CompatibilityLeadsRevenueResult([]);

        foreach ($leads as $result) {
            $lead = new CompatibilityLeadsRevenueResult($result);

            if ($lead->totalAmount() < $leadExpensive->totalAmount() || $leadExpensive->totalAmount() === 0) {
                $leadExpensive = $lead;
            }
        }

        return $leadExpensive;
    }

    public function supports($key)
    {
        return 'cheaper' === $key;
    }
}
