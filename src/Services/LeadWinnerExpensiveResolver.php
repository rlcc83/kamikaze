<?php

namespace App\Services;

use App\Interface\LeadWinnerResolverInterface;
use App\Model\CompatibilityLeadsRevenueResult;

class LeadWinnerExpensiveResolver implements LeadWinnerResolverInterface
{
    public function resolve(array $leads): CompatibilityLeadsRevenueResult
    {
        $leadExpensive = new CompatibilityLeadsRevenueResult([]);

        foreach ($leads as $result) {
            $lead = new CompatibilityLeadsRevenueResult($result);

            if ($lead->totalAmount() > $leadExpensive->totalAmount()) {
                $leadExpensive = $lead;
            }
        }

        return $leadExpensive;
    }

    public function supports($key)
    {
        return 'expensive' === $key;
    }
}
