<?php

namespace App\Interface;

use App\Model\CompatibilityLeadsRevenueResult;
use DateTimeInterface;

interface CompatibilityLeads
{
    const EXPENSIVE = 'expensive';
    const CHEAPER = 'cheaper';
    const STRATEGY_DEFAULT = 'expensive';
    const STRATEGIES = [self::EXPENSIVE, self::CHEAPER];

    public function execute(DateTimeInterface $dateStart, DateTimeInterface $dateEnd, ?string $strategy = null): CompatibilityLeadsRevenueResult;
}
