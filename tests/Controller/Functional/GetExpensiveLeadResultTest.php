<?php

namespace App\Tests\Controller\Functional;

use App\Repository\LeadRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group compatiblityLeadsRevenue
 */
final class GetExpensiveLeadResultTest extends WebTestCase
{
    public function testMostRevenue()
    {
        $client = static::createClient();
        $leadRepository = static::getContainer()->get(LeadRepository::class);

        $client->request('GET', '/calculate');
        $response = $client->getResponse();
        self::assertStringContainsString('Molina', $response);
        self::assertStringContainsString('Mijas', $response);
        self::assertStringContainsString('32000', $response);
    }

    public function testLessRevenue()
    {
        $client = static::createClient();
        $leadRepository = static::getContainer()->get(LeadRepository::class);

        $client->request('GET', '/calculate-cheaper');
        $response = $client->getResponse();
        self::assertStringContainsString('Tenerife', $response);
        self::assertStringContainsString('Arturo', $response);
        self::assertStringContainsString('26000', $response);
    }

    
}
