<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScheduleProgram|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleProgram|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleProgram[]    findAll()
 * @method ScheduleProgram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleProgramRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScheduleProgram::class);
    }

    /**
     * @param $value
     *
     * @return ScheduleProgram|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByProgramId($value): ?ScheduleProgram
    {
        return $this->createQueryBuilder('sp')
            ->andWhere('sp.programId = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
