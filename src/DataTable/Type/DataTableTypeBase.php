<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity\Show;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DataTableTypeBase
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * AssetsTableType constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $router
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UrlGeneratorInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * Create a linked string to a Show page.
     *
     * @param $context
     *   Either a Show entity or an entity with a getShow() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Show title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderShowLink($context, $value) {
        /** @var Show $entity */
        if (get_class($context) == Show::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getShow')) {
            $entity = $context->getShow();
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate(
            'media_manager_shows_show',
            ['id' => $entity->getId()]
        );
        return sprintf('<a href="%s">%s</a>', $url, (string) $entity);
    }
}