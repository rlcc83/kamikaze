<?php

namespace App\DataFixtures;

use App\Entity\Lead;
use App\Repository\LeadRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LeadFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        protected LeadRepository $leadRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $items = [
            [
                'name' => 'Molina',
                'dateStart' => new \DateTime('2022-01-01'),
                'dateEnd' => new \DateTime('2022-01-15'),
                'revenue' => '14000'
            ],
            [
                'name' => 'Tenerife',
                'dateStart' => new \DateTime('2022-01-04'),
                'dateEnd' => new \DateTime('2022-01-07'),
                'revenue' => '7000'
            ],
            [
                'name' => 'Arturo',
                'dateStart' => new \DateTime('2022-01-07'),
                'dateEnd' => new \DateTime('2022-01-24'),
                'revenue' => '19000'
            ],
            [
                'name' => 'Mijas',
                'dateStart' => new \DateTime('2022-01-15'),
                'dateEnd' => new \DateTime('2022-01-31'),
                'revenue' => '18000'
            ],
        ];

        foreach ($items as $item) {
            $lead = Lead::create(
                $item['name'],
                $item['dateStart'],
                $item['dateEnd'],
                $item['revenue']
            );

            $this->leadRepository->save($lead);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
