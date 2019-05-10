<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
use CascadePublicMedia\PbsApiExplorer\Entity\Episode;
use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\Image;
use CascadePublicMedia\PbsApiExplorer\Entity\Season;
use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use CascadePublicMedia\PbsApiExplorer\Service\MediaManagerApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaManagerController
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 *
 * TODO: Convert DataTableFactory usages to DataTable Types.
 */
class MediaManagerController extends ControllerBase
{
    private static $notConfigured = 'The Media Manager API has not been configured. Visit Settings to configure it.';

    /**
     * @Route("/media-manager", name="media_manager")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('media_manager/index.html.twig', [
            'controller_name' => 'MediaManagerController',
        ]);
    }

    /**
     * @Route("/media-manager/genres", name="media_manager_genres")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function genres(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('created', DateTimeColumn::class, ['label' => 'Created'])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Genre::class])
            ->addOrderBy('title', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Genres',
            'update_route' => 'media_manager_genres_update'
        ]);
    }

    /**
     * @Route("/media-manager/genres/update", name="media_manager_genres_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function genres_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $this->updateAll($apiClient, Genre::class);
        return $this->redirectToRoute('media_manager_genres');
    }

    /**
     * @Route("/media-manager/franchises", name="media_manager_franchises")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function franchises(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('genre', TextColumn::class, [
                'data' => '<em>None</em>',
                'raw' => true,
                'field' => 'genre.title',
                'label' => 'Genre'
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Franchise::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('franchise')
                        ->addSelect('genre')
                        ->from(Franchise::class, 'franchise')
                        ->leftJoin('franchise.genre', 'genre')
                    ;
                },
            ])
            ->addOrderBy('title', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Franchises',
            'update_route' => 'media_manager_franchises_update'
        ]);
    }

    /**
     * @Route("/media-manager/franchises/update", name="media_manager_franchises_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function franchises_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $this->updateAll(
            $apiClient,
            Franchise::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        return $this->redirectToRoute('media_manager_franchises');
    }

    /**
     * @Route("/media-manager/shows", name="media_manager_shows")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function shows(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('title', TextColumn::class, [
                'label' => 'Title',
            ])
            ->add('slug', TextColumn::class, ['label' => 'Slug'])
            ->add('franchise', TextColumn::class, [
                'data' => '<em>None</em>',
                'field' => 'franchise.title',
                'label' => 'Franchise',
                'raw' => true,
            ])
            ->add('genre', TextColumn::class, [
                'data' => '<em>None</em>',
                'field' => 'genre.title',
                'label' => 'Genre',
                'raw' => true,
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Show::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('show')
                        ->addSelect('franchise')
                        ->addSelect('genre')
                        ->from(Show::class, 'show')
                        ->leftJoin('show.franchise', 'franchise')
                        ->leftJoin('show.genre', 'genre')
                    ;
                },
            ])
            ->addOrderBy('updated', DataTable::SORT_DESCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Shows',
            /*'entity_route' => 'media_manager_shows_show',*/
            'update_route' => 'media_manager_shows_update',
        ]);
    }

    /**
     * @Route("/media-manager/shows/update", name="media_manager_shows_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function shows_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $this->updateAll(
            $apiClient,
            Show::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        return $this->redirectToRoute('media_manager_shows');
    }

    /**
     * @Route("/media-manager/shows/{id}", name="media_manager_shows_show")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $id
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function show($id, EntityManagerInterface $entityManager) {
        $show = $entityManager
            ->getRepository(Show::class)
            ->find($id);

        if (!$show) {
            throw new NotFoundHttpException();
        }

        return $this->render('media_manager/show.html.twig', [
            'show' => $show,
        ]);
    }

    /**
     * @Route("/media-manager/seasons", name="media_manager_seasons")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function seasons(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('show', TextColumn::class, [
                'field' => 'show.title',
                'label' => 'Show',
            ])
            ->add('ordinal', TextColumn::class, ['label' => 'Ordinal'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Season::class])
            ->addOrderBy('show', DataTable::SORT_ASCENDING)
            ->addOrderBy('ordinal', DataTable::SORT_DESCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Seasons',
        ]);
    }

    /**
     * @Route("/media-manager/episodes", name="media_manager_episodes")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function episodes(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('title', TextColumn::class, ['label' => 'Episode'])
            ->add('season', TextColumn::class, [
                'label' => 'Season',
                'field' => 'season.ordinal',
            ])
            ->add('show', TextColumn::class, [
                'label' => 'Show',
                'field' => 'show.title',
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, ['entity' => Episode::class])
            ->addOrderBy('show', DataTable::SORT_ASCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Episodes',
        ]);
    }

    /**
     * @Route("/media-manager/episodes/{showId}/update", name="media_manager_episodes_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string $showId
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function episodes_update($showId, MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $apiClient->updateEpisodesByShowId($showId);
        return $this->redirectToRoute('media_manager_shows_show', [
            'id' => $showId
        ]);
    }

    /**
     * @Route("/media-manager/assets", name="media_manager_assets")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function assets(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
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
            ->add('franchise', TextColumn::class, [
                'field' => 'franchise.title',
                'label' => 'Franchise',
            ])
            ->add('show', TextColumn::class, [
                'field' => 'show.title',
                'label' => 'Show',
            ])
            ->add('season', TextColumn::class, [
                'field' => 'season.title',
                'label' => 'Season',
            ])
            ->add('episode', TextColumn::class, [
                'field' => 'episode.title',
                'label' => 'Episode',
            ])
            ->add('updated', DateTimeColumn::class, ['label' => 'Updated'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Show::class,
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
            ->addOrderBy('updated', DataTable::SORT_DESCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Assets',
        ]);
    }

    /**
     * @Route("/media-manager/images", name="media_manager_images")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function images(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
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
                'entity' => Show::class,
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
            ->addOrderBy('updated', DataTable::SORT_DESCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Images',
        ]);
    }
}
