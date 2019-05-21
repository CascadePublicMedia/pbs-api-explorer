<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Entity;
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
            ->createAdapter(ORMAdapter::class, ['entity' => Entity\Genre::class])
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
        $stats = $apiClient->updateAllByEntityClass(Entity\Genre::class);
        $this->flashUpdateStats($stats);
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
                'entity' => Entity\Franchise::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('franchise')
                        ->addSelect('genre')
                        ->from(Entity\Franchise::class, 'franchise')
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
        $stats = $apiClient->updateAllByEntityClass(
            Entity\Franchise::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        $this->flashUpdateStats($stats);

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
            ->add('id', TextColumn::class, ['label' => 'ID'])
            ->add('title', TextColumn::class, ['label' => 'Title'])
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
                'entity' =>Entity\Show::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('show')
                        ->addSelect('franchise')
                        ->addSelect('genre')
                        ->from(Entity\Show::class, 'show')
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
        $stats = $apiClient->updateAllByEntityClass(
            Entity\Show::class,
            ['queryParameters' => ['fetch-related' => TRUE]]
        );
        $this->flashUpdateStats($stats);
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
            ->getRepository(Entity\Show::class)
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
            ->createAdapter(ORMAdapter::class, ['entity' => Entity\Season::class])
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
            ->createAdapter(ORMAdapter::class, ['entity' => Entity\Episode::class])
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
     * @Route("/media-manager/topics", name="media_manager_topics")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     *
     * @return Response
     */
    public function topics(DataTableFactory $dataTableFactory, Request $request) {
        $table = $dataTableFactory->create()
            ->add('name', TextColumn::class, ['label' => 'Name'])
            ->add('parent', TextColumn::class, [
                'label' => 'Parent',
                'field' => 'parent.name',
            ])
            ->add('updated', DateTimeColumn::class, [
                'label' => 'Updated (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' =>Entity\Topic::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('topic')
                        ->addSelect('parent')
                        ->from(Entity\Topic::class, 'topic')
                        ->leftJoin('topic.parent', 'parent')
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
            'title' => 'Topics',
            'update_route' => 'media_manager_topics_update',
        ]);
    }

    /**
     * @Route("/media-manager/topics/update", name="media_manager_topics_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     */
    public function topics_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateAllByEntityClass(Entity\Topic::class);
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_topics');
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
                'entity' => Entity\Show::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('asset')
                        ->addSelect('franchise')
                        ->addSelect('show')
                        ->addSelect('season')
                        ->addSelect('episode')
                        ->from(Entity\Asset::class, 'asset')
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
                'entity' => Entity\Show::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('image')
                        ->addSelect('franchise')
                        ->addSelect('show')
                        ->addSelect('asset')
                        ->addSelect('station')
                        ->from(Entity\Image::class, 'image')
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

    /**
     * @Route("/media-manager/changelog", name="media_manager_changelog")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param DataTableFactory $dataTableFactory
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function changelog(DataTableFactory $dataTableFactory,
                              Request $request,
                              EntityManagerInterface $entityManager) {
        $table = $dataTableFactory->create()
            ->add('activity', TextColumn::class, ['label' => 'Change type'])
            ->add('type', TextColumn::class, ['label' => 'Entity type'])
            ->add('resourceId', TextColumn::class, [
                'label' => 'Entity',
                'data' => function($context, $value) use ($entityManager) {
                    return self::renderChangelogEntity($context, $value, $entityManager);
                },
                'raw' => TRUE,
            ])
            ->add('updatedFields', TextColumn::class, [
                'label' => 'Updated fields',
                'data' => function($context, $value) {
                    return self::renderChangelogUpdatedFields($context, $value);
                },
                'raw' => TRUE,
            ])
            ->add('timestamp', DateTimeColumn::class, [
                'label' => 'Timestamp (UTC)',
                'format' => 'Y-m-d H:i:s',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Entity\ChangelogEntry::class
            ])
            ->addOrderBy('timestamp', DataTable::SORT_DESCENDING)
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('datatable.html.twig', [
            'datatable' => $table,
            'title' => 'Changelog',
            'update_route' => 'media_manager_changelog_update',
        ]);
    }

    /**
     * Create a string value for a locally synced resource in a Changelog.
     *
     * @param Entity\ChangelogEntry $context
     *   Changelog entity data
     * @param string $value
     *   Resource ID from the Changelog entry.
     * @param EntityManagerInterface $entityManager
     *   The Entity Manager service.
     *
     * @return string
     *   An entity title if the entity is available locally, the resource ID
     *   otherwise.
     */
    private static function renderChangelogEntity(Entity\ChangelogEntry $context, $value, EntityManagerInterface $entityManager) {
        $str = $value;
        if ($value) {
            $type = $context->getType();
            if ($type == 'remoteasset') {
                $type = 'RemoteAsset';
            }
            else {
                $type = ucfirst($type);
            }
            $class = sprintf(
                'CascadePublicMedia\PbsApiExplorer\Entity\%s',
                $type
            );
            if (class_exists($class)) {
                $entity = $entityManager->getRepository($class)->find($value);
                if ($entity) {
                    $str = sprintf(
                        '<strong>%s</strong><br/><code>%s</code>',
                        (string) $entity,
                        $value
                    );
                }
            }
        }
        return $str;
    }

    /**
     * Create a list out of an array of updated fields names for a Changelog.
     *
     * @param $context
     *   Changelog entity data
     * @param array $value
     *   Value of the "updated_fields" Changelog entry.
     *
     * @return string
     *   An HTML list of all array values, empty string otherwise.
     */
    private static function renderChangelogUpdatedFields(Entity\ChangelogEntry $context, array $value) {
        if (empty($value)) {
            return '';
        }

        $str = '<ul>';
        foreach ($value as $field) {
            $str .= sprintf('<li><code>%s</code></li>', $field);
        }
        $str .= '</ul>';

        return $str;
    }

    /**
     * @Route("/media-manager/changelog/update", name="media_manager_changelog_update")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param MediaManagerApiClient $apiClient
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function changelog_update(MediaManagerApiClient $apiClient) {
        if (!$apiClient->isConfigured()) {
            throw new NotFoundHttpException(self::$notConfigured);
        }
        $stats = $apiClient->updateChangelog();
        $this->flashUpdateStats($stats);
        return $this->redirectToRoute('media_manager_changelog');
    }
}
