<?php

namespace App\Repository;

use App\Entity\CourseNaviguation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseNaviguation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseNaviguation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseNaviguation[]    findAll()
 * @method CourseNaviguation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseNaviguationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseNaviguation::class);
    }

    // /**
    //  * @return CourseNaviguation[] Returns an array of CourseNaviguation objects
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
    public function findOneBySomeField($value): ?CourseNaviguation
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
