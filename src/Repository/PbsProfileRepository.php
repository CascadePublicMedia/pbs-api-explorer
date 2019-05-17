<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PbsProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PbsProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PbsProfile[]    findAll()
 * @method PbsProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PbsProfileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PbsProfile::class);
    }

    // /**
    //  * @return PbsProfile[] Returns an array of PbsProfile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PbsProfile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
