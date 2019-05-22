<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Episode;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class EpisodesTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('title', TextColumn::class, [
                'label' => 'Episode',
                'data' => function($context, $value) {
                    return $this->renderEpisodeLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('season', TextColumn::class, [
                'label' => 'Season',
                'field' => 'season.ordinal',
            ])
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
            ->createAdapter(ORMAdapter::class, ['entity' => Episode::class])
            ->addOrderBy('show', DataTable::SORT_ASCENDING);
    }
}
