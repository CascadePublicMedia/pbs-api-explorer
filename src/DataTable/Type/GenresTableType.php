<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class GenresTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('title', TextColumn::class, [
                'label' => 'Title',
                'data' => function($context, $value) {
                    return $this->renderGenreLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('created', DateTimeColumn::class, [
                'label' => 'Created (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Genre::class])
            ->addOrderBy('title', DataTable::SORT_ASCENDING);
    }
}
