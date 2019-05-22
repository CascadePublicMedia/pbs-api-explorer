<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\AssetTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AssetTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetTag[]    findAll()
 * @method AssetTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetTagRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AssetTag::class);
    }

    // /**
    //  * @return AssetTag[] Returns an array of AssetTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssetTag
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
