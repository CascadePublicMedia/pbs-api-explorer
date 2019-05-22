<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Headend;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class HeadendsTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('name', TextColumn::class, [
                'label' => 'Name',
                'data' => function($context, $value) {
                    return $this->renderHeadendLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Headend::class])
            ->addOrderBy('name', DataTable::SORT_ASCENDING);
    }
}
