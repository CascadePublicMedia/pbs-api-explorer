<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\Headend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Headend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Headend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Headend[]    findAll()
 * @method Headend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadendRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Headend::class);
    }

    // /**
    //  * @return Headend[] Returns an array of Headend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Headend
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
