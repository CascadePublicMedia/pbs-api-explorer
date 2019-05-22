<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class FranchisesTableType implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('genre', TextColumn::class, [
                'data' => '<em>None</em>',
                'raw' => true,
                'field' => 'genre.title',
                'label' => 'Genre'
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Franchise::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('franchise')
                        ->addSelect('genre')
                        ->from(Franchise::class, 'franchise')
                        ->leftJoin('franchise.genre', 'genre')
                    ;
                },
            ])
            ->addOrderBy('title', DataTable::SORT_ASCENDING);
    }
}
