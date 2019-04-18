<?php

namespace CascadePublicMedia\PbsApiExplorer\Controller;

use CascadePublicMedia\PbsApiExplorer\Service\PbsApiClientBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ControllerBase
 *
 * @package CascadePublicMedia\PbsApiExplorer\Controller
 */
class ControllerBase extends AbstractController
{
    /**
     * Process full entity updates from API.
     *
     * @param PbsApiClientBase $apiClient
     *   API client to use, should extend PbsApiClientBase.
     * @param $entityClass
     *   The Entity class to update.
     *
     * @todo Handle/report specific exceptions.
     */
    public function updateAll($apiClient, $entityClass) {
        $stats = $apiClient->updateAllByEntityClass($entityClass);
        $this->addFlash('success', sprintf(
            'Update complete! Local changes: %d added, %d updated, %d unchanged.',
            $stats['add'],
            $stats['update'],
            $stats['noop']
        ));
    }
}
