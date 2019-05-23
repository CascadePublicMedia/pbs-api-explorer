<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Membership;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class MembershipsTableType extends DataTableTypeBase implements DataTableTypeInterface
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
                    return $this->renderMembershipLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('profile', TextColumn::class, [
                'label' => 'Profile',
                'data' => function($context, $value) {
                    return $this->renderPbProfileLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('firstName', TextColumn::class, ['label' => 'First'])
            ->add('lastName', TextColumn::class, ['label' => 'Last'])
            ->add('email', TextColumn::class, ['label' => 'Email'])
            ->add('status', TextColumn::class, ['label' => 'Status'])
            ->add('startDate', DateTimeColumn::class, [
                'label' => 'Start',
                'format' => 'Y-m-d',
            ])
            ->add('activationDate', DateTimeColumn::class, [
                'label' => 'Act.',
                'format' => 'Y-m-d',
            ])
            ->add('expireDate', DateTimeColumn::class, [
                'label' => 'Expire',
                'format' => 'Y-m-d',
            ])
            ->add('updateDate', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Membership::class])
            ->addOrderBy('updateDate', DataTable::SORT_DESCENDING);
    }
}
