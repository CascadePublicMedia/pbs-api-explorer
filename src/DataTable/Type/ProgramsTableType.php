<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ProgramsTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('programId', TextColumn::class, ['label' => 'ID'])
            ->add('title', TextColumn::class, [
                'label' => 'Title',
                'data' => function($context, $value) {
                    return $this->renderProgramLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('externalId', TextColumn::class, ['label' => 'External ID'])
            ->createAdapter(ORMAdapter::class, ['entity' => ScheduleProgram::class])
            ->addOrderBy('title', DataTable::SORT_ASCENDING);
    }
}
