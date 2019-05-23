<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class AssetsTableType extends DataTableTypeBase implements DataTableTypeInterface
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
                    return $this->renderAssetLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('type', MapColumn::class, [
                'default' => 'Unknown',
                'field' => 'asset.type',
                'label' => 'Type',
                'map' => [
                    'clip' => 'Clip',
                    'full_length' => 'Full length',
                    'preview' => 'Preview',
                ]
            ])
            ->add('id', TextColumn::class, [
                'label' => 'Parent',
                'data' => function($context, $value) {
                    return $this->renderParentEntity($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('franchiseId', TextColumn::class, [
                'label' => 'Franchise ID',
                'field' => 'franchise.id',
                'visible' => FALSE,
            ])
            ->add('showId', TextColumn::class, [
                'label' => 'Show ID',
                'field' => 'show.id',
                'visible' => FALSE,
            ])
            ->add('seasonId', TextColumn::class, [
                'label' => 'Season ID',
                'field' => 'season.id',
                'visible' => FALSE,
            ])
            ->add('episodeId', TextColumn::class, [
                'label' => 'Episode ID',
                'field' => 'episode.id',
                'visible' => FALSE,
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Asset::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('asset')
                        ->addSelect('franchise')
                        ->addSelect('show')
                        ->addSelect('season')
                        ->addSelect('episode')
                        ->from(Asset::class, 'asset')
                        ->leftJoin('asset.franchise', 'franchise')
                        ->leftJoin('asset.show', 'show')
                        ->leftJoin('asset.season', 'season')
                        ->leftJoin('asset.episode', 'episode')
                    ;
                },
            ])
            ->addOrderBy('updated', DataTable::SORT_DESCENDING);
    }

    /**
     * Create a (potentially linked) string representing an Asset's parent.
     *
     * @param Asset $context
     *   The Asset entity being evaluated.
     * @param string $value
     *   The Asset ID (unused).
     *
     * @return string
     *   A string with the parent entity's name, otherwise 'Unknown'.
     */
    private function renderParentEntity(Asset $context, $value) {
        if ($entity = $context->getFranchise()) {
            $route = 'media_manager_franchises_franchise';
        }
        elseif ($entity = $context->getShow()) {
            $route = 'media_manager_shows_show';
        }
        elseif ($entity = $context->getSeason()) {
            $route = 'media_manager_seasons_season';
        }
        elseif ($entity = $context->getEpisode()) {
            $route = 'media_manager_episodes_episode';
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate($route, ['id' => $entity->getId()]);
        return sprintf(
            '<a href="%s">%s</a> <span class="entity-type">%s</span>',
            $url,
            (string) $entity,
            $entity::NAME
        );
    }
}
