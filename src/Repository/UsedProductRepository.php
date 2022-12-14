<?php

namespace App\Repository;

use App\Entity\UsedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsedProduct[]    findAll()
 * @method UsedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsedProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsedProduct::class);
    }

    // /**
    //  * @return UsedProduct[] Returns an array of UsedProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsedProduct
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
