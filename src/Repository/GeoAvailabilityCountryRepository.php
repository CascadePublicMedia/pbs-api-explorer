<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GeoAvailabilityCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoAvailabilityCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoAvailabilityCountry[]    findAll()
 * @method GeoAvailabilityCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoAvailabilityCountryRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeoAvailabilityCountry::class);
    }

    // /**
    //  * @return GeoAvailabilityCountry[] Returns an array of GeoAvailabilityCountry objects
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
    public function findOneBySomeField($value): ?GeoAvailabilityCountry
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
