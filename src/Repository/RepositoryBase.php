<?php

namespace CascadePublicMedia\PbsApiExplorer\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * Retrieve a single entity by ID with all associations eager loading.
     *
     * @param $id
     *
     * @return mixed
     *
     * @throws MappingException
     * @throws NonUniqueResultException
     */
    public function findEager($id) {
        $query = $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $metadata = $this->getClassMetadata();
        $associations = $metadata->getAssociationMappings();
        foreach ($associations as $association) {
            $metadata->setAssociationOverride(
                $association['fieldName'],
                ['fetch' => ClassMetadataInfo::FETCH_EAGER]
            );
        }

        return $query->getOneOrNullResult();
    }
}
