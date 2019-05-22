<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Season;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class SeasonsTableType extends DataTableTypeBase implements DataTableTypeInterface
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
                    return $this->renderSeasonLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('ordinal', NumberColumn::class, ['label' => 'Ordinal'])
            ->add('show', TextColumn::class, [
                'field' => 'show.title',
                'label' => 'Show',
                'data' => function($context, $value) {
                    return $this->renderShowLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Season::class])
            ->addOrderBy('show', DataTable::SORT_ASCENDING)
            ->addOrderBy('ordinal', DataTable::SORT_DESCENDING);
    }
}
