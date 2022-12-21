<?php

namespace App\Tests\Controller\Unit;

use App\Model\CompatibilityLeadsRevenueResult;
use PHPUnit\Framework\TestCase;
use App\Repository\LeadRepository;
use App\Services\CompatibilityLeadsByDate;
use App\Services\LeadWinner;
use App\Services\GetWinnerLead;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group compatiblityLeadsRevenue
 */
final class CreatedLeadControllerUnitTest extends TestCase
{
    private MockObject|LeadRepository $leadRepository;
    private MockObject|CompatibilityLeadsByDate $compatibilityLeadsByDate;
    private MockObject|LeadWinner $leadWinner;
    private GetWinnerLead $service;

    public function setUp(): void
    {
        $this->leadRepository = $this->getMockBuilder(LeadRepository::class)->disableOriginalConstructor()->getMock();
        $this->compatibilityLeadsByDate = $this->getMockBuilder(CompatibilityLeadsByDate::class)->disableOriginalConstructor()->getMock();
        $this->leadWinner = $this->getMockBuilder(LeadWinner::class)->disableOriginalConstructor()->getMock();

        $this->service = new GetWinnerLead($this->leadRepository, $this->compatibilityLeadsByDate, $this->leadWinner);
    }

    public function testGetExpensiveLead(): void
    {
        $dateStart = new \DateTime('2022-01-01');
        $dateEnd = new \DateTime('2022-01-31');

        $response = $this->service->execute($dateStart, $dateEnd);
        self::assertInstanceOf(CompatibilityLeadsRevenueResult::class, $response);
    }
}
