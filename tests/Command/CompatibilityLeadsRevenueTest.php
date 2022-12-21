<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group compatiblityLeadsRevenue
 */
final class CompatibilityLeadsRevenueTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:compatibility-leads');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Name: Molina. Profit: 14000', $output);
        $this->assertStringContainsString('Name: Mijas. Profit: 18000', $output);
        $this->assertStringContainsString('TOTAL REVENUE: 32000', $output);
    }
}