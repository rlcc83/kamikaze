<?php

namespace App\Services;

use App\Interface\LeadWinnerResolverInterface;
use App\Model\CompatibilityLeadsRevenueResult;
use InvalidArgumentException;

use function PHPUnit\Framework\throwException;

class LeadWinner //implements LeadWinnerResolverInterface
{
    public function __construct(
        private iterable $leadsWinner = []
    ) {
    }

    public function resolve(array $leads, string $key): CompatibilityLeadsRevenueResult
    {
        foreach ($this->leadsWinner as $leadResolver) {
            if($leadResolver->supports($key)) {
                return $leadResolver->resolve($leads);
            }
        }

        throw new InvalidArgumentException('No strategy found');
    }
}
