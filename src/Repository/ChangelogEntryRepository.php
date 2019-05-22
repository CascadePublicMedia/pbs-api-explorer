<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\ChangelogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChangelogEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChangelogEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChangelogEntry[]    findAll()
 * @method ChangelogEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChangelogEntryRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChangelogEntry::class);
    }

    public function findLastUpdated(): ?ChangelogEntry
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return ChangelogEntry[] Returns an array of ChangelogEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChangelogEntry
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
