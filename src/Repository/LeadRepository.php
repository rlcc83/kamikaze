<?php

namespace App\Repository;

use App\Entity\Lead;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lead|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lead|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lead[]    findAll()
 * @method Lead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lead::class);
    }

    public function save(Lead $lead)
    {
        $this->getEntityManager()->persist($lead);
        $this->getEntityManager()->flush();

        return $lead;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Lead $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Lead $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function compatibleLeads(Lead $lead, DateTime $periodInitial, DateTime $periodEnd): array
    {
        $results = $this->createQueryBuilder('l')
            ->where('l.dateStart <= :dateStart AND l.dateEnd <= :dateStart')
            ->orWhere('l.dateStart >= :dateEnd')
            ->orWhere('l = :lead')
            ->andWhere('l.dateStart >= :periodInitial AND l.dateEnd <= :periodEnd')
            ->setParameter('dateStart', $lead->getDateStart())
            ->setParameter('dateEnd', $lead->getDateEnd())
            ->setParameter('periodInitial', $periodInitial)
            ->setParameter('periodEnd', $periodEnd)
            ->setParameter('lead', $lead)
            ->getQuery()
            ->getResult()
        ;

        return $results;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findBetweenDates(DateTime $periodInitial, DateTime $periodEnd): array
    {
        $results = $this->createQueryBuilder('l')
            ->where('l.dateStart >= :periodInitial AND l.dateEnd <= :periodEnd')
            ->setParameter('periodInitial', $periodInitial)
            ->setParameter('periodEnd', $periodEnd)
            ->getQuery()
            ->getResult()
        ;

        return $results;
    }
}
