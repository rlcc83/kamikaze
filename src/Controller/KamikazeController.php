<?php

namespace App\Controller;

use App\Interface\CompatibilityLeads;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\GetWinnerLead;

class KamikazeController extends AbstractController
{
    public function __construct(
        private GetWinnerLead $getWinnerLead
    ) {
    }

    #[Route('/calculate', name: 'calculate')]
    public function calculate(): Response
    {
        $periodInitial = new \DateTime('2022-01-01');
        $periodEnd = new \DateTime('2022-01-31');

        $leadRevenue = $this->getWinnerLead->execute($periodInitial, $periodEnd);

        return $this->render('default/kamikaze.html.twig', [
            'projects' => $leadRevenue,
        ]);
    }

    #[Route('/calculate-cheaper', name: 'calculate-cheaper')]
    public function calculateCheaper(): Response
    {
        $periodInitial = new \DateTime('2022-01-01');
        $periodEnd = new \DateTime('2022-01-31');

        $leadRevenue = $this->getWinnerLead->execute($periodInitial, $periodEnd, CompatibilityLeads::CHEAPER);

        return $this->render('default/kamikaze.html.twig', [
            'projects' => $leadRevenue,
            'strategy' => CompatibilityLeads::CHEAPER
        ]);
    }
}
