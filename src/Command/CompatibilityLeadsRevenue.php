<?php

namespace App\Command;

use App\Services\GetWinnerLead;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:compatibility-leads')]
class CompatibilityLeadsRevenue extends Command
{
    private $getWinnerLead;

    public function __construct(GetWinnerLead $getWinnerLead)
    {
        $this->getWinnerLead = $getWinnerLead;

        parent::__construct();
    }

    protected static $defaultDescription = 'Creates a new user.';

    protected function configure(): void
    {
        $this
            ->setHelp('Print the combination of project with most revenue without dates overlapping')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dateStart = new \DateTime('2022-01-01');
        $dateEnd = new \DateTime('2022-01-31');

        $leadWinner = $this->getWinnerLead->execute($dateStart, $dateEnd);

        $output->writeln($this->messageFormatted($dateStart, $dateEnd, $leadWinner));

        return Command::SUCCESS;
    }

    protected function messageFormatted($dateStart, $dateEnd, $leadWinner)
    {
        $message[] = '===========================================================================';
        $message[] = sprintf('This is the combination with more revenue between %s and %s', $dateStart->format('d/m/Y'), $dateEnd->format('d/m/Y'));
        $message[] = '===========================================================================';

        foreach ($leadWinner->getLeads() as $leads) {
            $message[] = sprintf('Name: %s. Profit: %s', $leads->getName(), $leads->getProfit());
            $message[] = '---------------------------------------------------------------------------';
        }

        $message[] = '===========================================================================';
        $message[] = sprintf('TOTAL REVENUE: %s', $leadWinner->totalAmount());
        $message[] = '===========================================================================';

        return $message;
    }
}
