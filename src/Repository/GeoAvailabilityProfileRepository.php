<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GeoAvailabilityProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoAvailabilityProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoAvailabilityProfile[]    findAll()
 * @method GeoAvailabilityProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoAvailabilityProfileRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeoAvailabilityProfile::class);
    }

    // /**
    //  * @return GeoAvailabilityProfile[] Returns an array of GeoAvailabilityProfile objects
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
    public function findOneBySomeField($value): ?GeoAvailabilityProfile
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
