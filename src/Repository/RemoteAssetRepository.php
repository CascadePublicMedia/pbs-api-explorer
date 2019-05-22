<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\RemoteAsset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RemoteAsset|null find($id, $lockMode = null, $lockVersion = null)
 * @method RemoteAsset|null findOneBy(array $criteria, array $orderBy = null)
 * @method RemoteAsset[]    findAll()
 * @method RemoteAsset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemoteAssetRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RemoteAsset::class);
    }

    // /**
    //  * @return RemoteAsset[] Returns an array of RemoteAsset objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RemoteAsset
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
