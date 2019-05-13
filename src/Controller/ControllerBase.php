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
     * @param array $config
     *   (optional) Additional configuration options to pass on to the update
     *   method.
     *
     * @return array
     *  Stats from the update process.
     *
     * @see PbsApiClientBase::update()
     *
     * @todo Handle/report specific exceptions.
     */
    public function updateAll($apiClient, $entityClass, array $config = []) {
        return $apiClient->updateAllByEntityClass($entityClass, $config);
    }

    /**
     * Add a generic flash message from stats after an update process.
     *
     * @param array $stats
     *   Stats from an update process. Should contain the keys:
     *    - add
     *    - update
     *    - noop
     *
     * @see PbsApiClientBase::update()
     */
    public function flashUpdateStats($stats) {
        $this->addFlash('success', sprintf(
            'Update complete! Local changes: %d added, %d updated, %d unchanged.',
            $stats['add'],
            $stats['update'],
            $stats['noop']
        ));
    }
}
