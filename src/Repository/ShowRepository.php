<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Show|null find($id, $lockMode = null, $lockVersion = null)
 * @method Show|null findOneBy(array $criteria, array $orderBy = null)
 * @method Show[]    findAll()
 * @method Show[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Show::class);
    }

    public function findOneBySlug($value): ?Show
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.slug = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Show[] Returns an array of Show objects
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
    public function findOneBySomeField($value): ?Show
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
