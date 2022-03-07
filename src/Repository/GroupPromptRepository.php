<?php

namespace App\Repository;

use App\Entity\GroupPrompt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupPrompt|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupPrompt|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupPrompt[]    findAll()
 * @method GroupPrompt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupPromptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupPrompt::class);
    }

    // /**
    //  * @return GroupPrompt[] Returns an array of GroupPrompt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupPrompt
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
