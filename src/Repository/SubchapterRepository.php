<?php

namespace App\Repository;

use App\Entity\Subchapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subchapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subchapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subchapter[]    findAll()
 * @method Subchapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubchapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subchapter::class);
    }

    // /**
    //  * @return Subchapter[] Returns an array of Subchapter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subchapter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
