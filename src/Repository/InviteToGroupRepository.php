<?php

namespace App\Repository;

use App\Entity\InviteToGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InviteToGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method InviteToGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method InviteToGroup[]    findAll()
 * @method InviteToGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviteToGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InviteToGroup::class);
    }

    // /**
    //  * @return InviteToGroup[] Returns an array of InviteToGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InviteToGroup
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
