<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Listing;
use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ListingsTableType extends DataTableTypeBase implements DataTableTypeInterface
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
            ->add('program', TextColumn::class, [
                'field' => 'program.title',
                'label' => 'Program',
                'data' => function($context, $value) {
                    return $this->renderProgramLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('title', TextColumn::class, [
                'label' => 'Title',
                'data' => function($context, $value) {
                    return $this->renderListingLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('episodeTitle', TextColumn::class, [
                'label' => 'Episode title',
                'visible' => FALSE,
            ])
            ->add('externalId', TextColumn::class, [
                'field' => 'program.externalId',
                'data' => function($context, $value) {
                    return $this->renderMediaManagerShow($context, $value);
                },
                'label' => 'Show (MM)',
                'raw' => TRUE,
            ])
            ->createAdapter(ORMAdapter::class, ['entity' => Listing::class])
            ->addOrderBy('feed', DataTable::SORT_DESCENDING)
            ->addOrderBy('date', DataTable::SORT_DESCENDING)
            ->addOrderBy('startTime', DataTable::SORT_ASCENDING);
    }

    /**
     * Attempt to relate a listing program to a Media Manager show.
     *
     * @param Listing $context
     * @param string $value
     *
     * @return string
     */
    public function renderMediaManagerShow($context, $value) {
        $str = sprintf('<strong>NOT FOUND:</strong> %s', $value);

        /** @var ScheduleProgram $program */
        $program = $context->getProgram();
        if ($id = $program->getExternalId()) {

            // TMS ID match.
            /** @var Show|null $show */
            $show = $this->entityManager
                ->getRepository(Show::class)
                ->findOneByTmsId($id);
            $type = 'TMS ID';

            // Exact title string match.
            if (!$show) {
                /** @var Show|null $show */
                $show = $this->entityManager
                    ->getRepository(Show::class)
                    ->findOneByTitle($program->getTitle());
                $type = 'Title';
            }

            if ($show) {
                $str = $this->renderShowLink($show, null);
                $str .= sprintf('<div class="entity-type">%s</div>', $type);
            }
        }

        return $str;
    }
}
