<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Image;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ImagesTableType implements DataTableTypeInterface
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
            ->add('image', TextColumn::class, [
                'field' => 'image.image',
                'label' => 'Image',
                'raw' => true,
                'render' => '<a href="%s">Link</a>',
            ])
            ->add('profile', TextColumn::class, [
                'field' => 'image.profile',
                'label' => 'Profile',
            ])
            ->add('franchise', TextColumn::class, [
                'field' => 'franchise.title',
                'label' => 'Franchise',
            ])
            ->add('show', TextColumn::class, [
                'field' => 'show.title',
                'label' => 'Show',
            ])
            ->add('asset', TextColumn::class, [
                'field' => 'asset.title',
                'label' => 'Asset',
            ])
            ->add('station', TextColumn::class, [
                'field' => 'station.fullCommonName',
                'label' => 'Station',
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
}
