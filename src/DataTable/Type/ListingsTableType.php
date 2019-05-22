<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Listing;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ListingsTableType implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('feed', TextColumn::class, [
                'field' => 'feed.fullName',
                'label' => 'Feed',
            ])
            ->add('date', DateTimeColumn::class, [
                'label' => 'Date',
                'format' => 'Y-m-d',
            ])
            ->add('startTime', TextColumn::class, ['label' => 'Start time'])
            ->add('durationMinutes', NumberColumn::class, ['label' => 'Duration'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('episodeTitle', TextColumn::class, ['label' => 'Episode title'])
            ->createAdapter(ORMAdapter::class, ['entity' => Listing::class])
            ->addOrderBy('feed', DataTable::SORT_DESCENDING)
            ->addOrderBy('date', DataTable::SORT_DESCENDING)
            ->addOrderBy('startTime', DataTable::SORT_ASCENDING);
    }
}
