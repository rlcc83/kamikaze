<?php

namespace App\Services;

use App\Interface\CompatibilityLeads;
use App\Repository\LeadRepository;
use App\Interface\CompatibilityLeadsInterface;
use App\Model\CompatibilityLeadsRevenueResult;
use DateTimeInterface;

class GetWinnerLead implements CompatibilityLeads
{
    public function __construct(
        private LeadRepository $leadRepository,
        private CompatibilityLeadsInterface $compatibilityLeadsService,
        private LeadWinner $getLeadProfit
    ) {
    }

    public function execute(DateTimeInterface $dateStart, DateTimeInterface $dateEnd, ?string $strategy = null): CompatibilityLeadsRevenueResult
    {
        $leads = $this->leadRepository->findBetweenDates($dateStart, $dateEnd);

        $results = $this->compatibilityLeadsService->getCompatibles($leads);

        $leadWinner = $this->getLeadProfit->resolve($results, $this->checkStrategy($strategy));

        return $leadWinner;
    }

    private function checkStrategy($strategy)
    {
        if(!in_array($strategy, CompatibilityLeads::STRATEGIES)) {
            return CompatibilityLeads::STRATEGY_DEFAULT;
        }

        return $strategy;
    }
}
