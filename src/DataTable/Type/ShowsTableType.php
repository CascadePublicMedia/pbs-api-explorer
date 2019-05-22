<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ShowsTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('id', TextColumn::class, [
                'label' => 'Title',
                'data' => function($context, $value) {
                    return $this->renderShowLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('franchise', TextColumn::class, [
                'field' => 'franchise.title',
                'label' => 'Franchise',
                'data' => function($context, $value) {
                    return $this->renderFranchiseLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('genre', TextColumn::class, [
                'field' => 'genre.title',
                'label' => 'Genre',
                'data' => function($context, $value) {
                    return $this->renderGenreLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' =>Show::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('show')
                        ->addSelect('franchise')
                        ->addSelect('genre')
                        ->from(Show::class, 'show')
                        ->leftJoin('show.franchise', 'franchise')
                        ->leftJoin('show.genre', 'genre')
                    ;
                },
            ])
            ->addOrderBy('updated', DataTable::SORT_DESCENDING);
    }
}
