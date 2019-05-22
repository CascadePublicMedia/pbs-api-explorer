<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AssetsTableType implements DataTableTypeInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * AssetsTableType constructor.
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('title', TextColumn::class, ['label' => 'Title'])
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
                    return $this->renderAssetParentEntity($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
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
    private function renderAssetParentEntity(Asset $context, $value) {
        if ($context->getFranchise()) {
            $entity = $context->getFranchise();
            $str = (string) $entity;
        }
        elseif ($context->getShow()) {
            $entity = $context->getShow();
            $url = $this->router->generate(
                'media_manager_shows_show',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        elseif ($context->getSeason()) {
            $entity = $context->getSeason();
            $str = (string) $entity;
        }
        elseif ($context->getEpisode()) {
            $entity = $context->getEpisode();
            $str = (string) $entity;
        }
        else {
            return 'Unknown';
        }
        return sprintf(
            '%s <span class="entity-type">%s</span>',
            $str,
            $entity::NAME
        );
    }
}
