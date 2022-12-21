<?php

namespace App\Services;

use App\Interface\LeadWinnerResolverInterface;
use App\Model\CompatibilityLeadsRevenueResult;

class LeadWinnerEarlyResolver implements LeadWinnerResolverInterface
{
    public function resolve(array $leads): CompatibilityLeadsRevenueResult
    {
        $leadExpensive = new CompatibilityLeadsRevenueResult([]);

        foreach ($leads as $result) {
            $lead = new CompatibilityLeadsRevenueResult($result);

            if ($lead->getDateStart() < $leadExpensive->getDateEnd()) {
                $leadExpensive = $lead;
            }
        }

        return $leadExpensive;
    }

    public function supports($key)
    {
        return 'dates' === $key;
    }
}
