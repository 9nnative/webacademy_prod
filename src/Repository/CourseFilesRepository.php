<?php

namespace App\Repository;

use App\Entity\CourseFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourseFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseFiles[]    findAll()
 * @method CourseFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseFiles::class);
    }

    // /**
    //  * @return CourseFiles[] Returns an array of CourseFiles objects
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
    public function findOneBySomeField($value): ?CourseFiles
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
