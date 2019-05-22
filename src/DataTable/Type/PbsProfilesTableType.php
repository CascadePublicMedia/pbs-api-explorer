<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class PbsProfilesTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('id', TextColumn::class, [
                'label' => 'ID',
                'data' => function($context, $value) {
                    return $this->renderPbProfileLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('firstName', TextColumn::class, ['label' => 'First'])
            ->add('lastName', TextColumn::class, ['label' => 'Last'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('loginProvider', TextColumn::class, ['label' => 'Provider'])
            ->createAdapter(ORMAdapter::class, ['entity' => PbsProfile::class])
            ->addOrderBy('lastName', DataTable::SORT_ASCENDING);
    }
}
