<?php

namespace App\Services;

use App\Entity\Lead;
use App\Interface\CompatibilityLeadsInterface;

class CompatibilityLeadsByDate implements CompatibilityLeadsInterface
{
    public function getCompatibles(array $leads): array
    {
        $compatibleLeadsFromEveryLead = array_map(function ($lead) use ($leads) {
            return $this->filterOverlapDates($lead, $leads);
        }, $leads);

        return $this->filterCompatiblesLeadsInGroup($compatibleLeadsFromEveryLead);
    }

    private function filterOverlapDates(Lead $lead, array $leads): array
    {
        $return = [];

        foreach ($leads as $leadCheck) {
            if ($leadCheck->getDateEnd() <= $lead->getDateStart()) {
                $return[] = $leadCheck;
            }

            if ($leadCheck->getDateStart() >= $lead->getDateEnd()) {
                $return[] = $leadCheck;
            }

            if ($lead === $leadCheck) {
                $return[] = $leadCheck;
            }
        }

        return $return;
    }

    private function filterCompatiblesLeadsInGroup(array $compatibleLeadsFromEveryLead): array
    {
        foreach ($compatibleLeadsFromEveryLead as $index => $leadsFromLead) {
            $leadsCompatiblesInsideLead[$index] = array_filter($leadsFromLead, function ($lead) use ($leadsFromLead) {
                return $this->areCompatible($lead, $leadsFromLead);
            });
        }

        return $leadsCompatiblesInsideLead;
    }

    private function areCompatible(Lead $lead, array $leadsFromLead): bool
    {
        foreach ($leadsFromLead as $leadCheck) {
            if ($lead === $leadCheck) {
                return true;
            }

            if ($lead->getDateStart() < $leadCheck->getDateEnd() &&  $lead->getDateEnd() > $leadCheck->getDateStart()) {
                return false;
            }
        }

        return false;
    }
}
