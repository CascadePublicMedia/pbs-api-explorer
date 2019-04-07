<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class RepositoryBase
 *
 * @package CascadePublicMedia\PbsApiExplorer\Repository
 */
class RepositoryBase extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @return mixed
     *
     * @throws QueryException
     */
    public function findAllIndexedById() {
        $qb = $this->createQueryBuilder('s');
        $query = $qb->indexBy('s', 's.id')->getQuery();
        return $query->getResult();
    }
}
