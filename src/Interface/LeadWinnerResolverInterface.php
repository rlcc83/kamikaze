<?php

namespace App\Interface;

use App\Model\CompatibilityLeadsRevenueResult;

interface LeadWinnerResolverInterface
{
    public function resolve(array $leads): CompatibilityLeadsRevenueResult;
}
