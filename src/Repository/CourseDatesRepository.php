<?php

namespace App\Repository;

use App\Entity\CourseDates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseDates|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseDates|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseDates[]    findAll()
 * @method CourseDates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseDatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseDates::class);
    }

    // /**
    //  * @return CourseDates[] Returns an array of CourseDates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CourseDates
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
