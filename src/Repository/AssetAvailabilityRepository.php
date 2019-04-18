<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\AssetAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AssetAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetAvailability[]    findAll()
 * @method AssetAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AssetAvailability::class);
    }

    /**
     * @param $asset
     * @return AssetAvailability[]
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllByAssetIndexedByType($asset)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.asset = :val')
            ->setParameter('val', $asset)
            ->indexBy('a', 'a.type')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return AssetAvailability[] Returns an array of AssetAvailability objects
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
    public function findOneBySomeField($value): ?AssetAvailability
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
