<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Season;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class SeasonsTableType implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('show', TextColumn::class, [
                'field' => 'show.title',
                'label' => 'Show',
            ])
            ->add('ordinal', TextColumn::class, ['label' => 'Ordinal'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Season::class])
            ->addOrderBy('show', DataTable::SORT_ASCENDING)
            ->addOrderBy('ordinal', DataTable::SORT_DESCENDING);
    }
}
