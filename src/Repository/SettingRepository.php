<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends RepositoryBase
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    /**
     * @param $value
     * @return mixed
     *
     * @throws QueryException
     */
    public function findByIdPrefix($value) {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id LIKE :val')
            ->setParameter('val', $value . '%')
            ->indexBy('s', 's.id')
            ->getQuery()
            ->getResult();
    }
}
