<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class StationsTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('fullCommonName', TextColumn::class, [
                'label' => 'Name',
                'data' => function($context, $value) {
                    return $this->renderStationLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('shortCommonName', TextColumn::class, ['label' => 'Name (short)'])
            ->add('callSign', TextColumn::class, ['label' => 'Call sign'])
            ->add('pdp', BoolColumn::class, [
                'label' => 'PDP',
                'className' => 'text-center',
                'trueValue' => '<i class="fas fa-check-circle text-green"></i>',
                'falseValue' => '<i class="fas fa-times-circle text-red"></i>',
            ])
            ->add('passportEnabled', BoolColumn::class, [
                'label' => 'Passport',
                'className' => 'text-center',
                'trueValue' => '<i class="fas fa-check-circle text-green"></i>',
                'falseValue' => '<i class="fas fa-times-circle text-red"></i>',
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Station::class])
            ->addOrderBy('fullCommonName', DataTable::SORT_ASCENDING);
    }
}
