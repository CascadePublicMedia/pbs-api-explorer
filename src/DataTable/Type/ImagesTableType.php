<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Image;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ImagesTableType extends DataTableTypeBase implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('image', TextColumn::class, [
                'field' => 'image.image',
                'label' => 'Image',
                'data' => function($context, $value) {
                    return $this->renderImageLink($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('profile', TextColumn::class, [
                'field' => 'image.profile',
                'label' => 'Profile',
            ])
            ->add('id', TextColumn::class, [
                'label' => 'Parent',
                'data' => function($context, $value) {
                    return $this->renderParentEntity($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Image::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('image')
                        ->addSelect('franchise')
                        ->addSelect('show')
                        ->addSelect('asset')
                        ->addSelect('station')
                        ->from(Image::class, 'image')
                        ->leftJoin('image.franchise', 'franchise')
                        ->leftJoin('image.show', 'show')
                        ->leftJoin('image.asset', 'asset')
                        ->leftJoin('image.station', 'station')
                    ;
                },
            ])
            ->addOrderBy('updated', DataTable::SORT_DESCENDING);
    }

    /**
     * Create a (potentially linked) string representing an Asset's parent.
     *
     * @param Image $context
     *   The Image entity being evaluated.
     * @param string $value
     *   The Image ID (unused).
     *
     * @return string
     *   A string with the parent entity's name, otherwise 'Unknown'.
     */
    private function renderParentEntity(Image $context, $value) {
        if ($entity = $context->getFranchise()) {
            $route = 'media_manager_franchises_franchise';
        }
        elseif ($entity = $context->getShow()) {
            $route = 'media_manager_shows_show';
        }
        elseif ($entity = $context->getAsset()) {
            $route = 'media_manager_assets_asset';
        }
        elseif ($entity = $context->getStation()) {
            $route = 'station_manager_stations_station';
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
