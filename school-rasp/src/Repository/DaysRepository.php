<?php

namespace App\Repository;

use App\Entity\Days;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Days|null find($id, $lockMode = null, $lockVersion = null)
 * @method Days|null findOneBy(array $criteria, array $orderBy = null)
 * @method Days[]    findAll()
 * @method Days[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Days::class);
    }

    // /**
    //  * @return Days[] Returns an array of Days objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Days
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
