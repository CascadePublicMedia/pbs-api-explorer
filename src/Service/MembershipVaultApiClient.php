<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Membership;
use CascadePublicMedia\PbsApiExplorer\Entity\PbsProfile;
use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class MembershipVaultApiClient
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class MembershipVaultApiClient extends PbsApiClientBase
{
    /**
     * @var array
     */
    protected $requiredSettings = [
        'mvault_base_uri' => 'Endpoint',
        'mvault_station_id' => 'Station ID',
        'mvault_client_id' => 'Client ID',
        'mvault_client_secret' => 'Client secret',
    ];

    /**
     * MembershipVaultApiClient constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param ApiValueProcessor $apiValueProcessor
     */
    public function __construct(EntityManagerInterface $entityManager, FieldMapper $fieldMapper, ApiValueProcessor $apiValueProcessor)
    {
        parent::__construct($entityManager, $fieldMapper, $apiValueProcessor);

        if ($this->isConfigured()) {
            /** @var Setting[] $settings */
            $settings = $entityManager
                ->getRepository(Setting::class)
                ->findByIdPrefix('mvault');

            $this->createClient([
                'base_uri' => $settings['mvault_base_uri']->getValue(),
                'auth' => [
                    $settings['mvault_client_id']->getValue(),
                    $settings['mvault_client_secret']->getValue()
                ],
            ]);
        }
    }

    /**
     * Update memberships data.
     *
     * @param array $config
     *
     * @return array
     *   Stats information from the update() method.
     *
     * TODO: Record last updated date and use that for future processing.
     */
    public function updateMemberships(array $config = []) {
        $config = self::createQueryConfig($config);
        unset($config['dataKey']);
        if (!isset($config['queryParameters']['page'])) {
            $config['queryParameters']['page'] = 1;
        }

        $url = $this->getSetting('mvault_station_id') . '/memberships';
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];
        $entities = $this->entityManager
            ->getRepository(Membership::class)
            ->findAll();
        $entities = new ArrayCollection($entities);

        while (true) {
            $items = $this->query($url, $config);
            $this->processItems(
                Membership::class,
                $entities,
                $items->objects,
                $stats
            );

            // Flush in 10,000 item increments.
            if ($this->entityManager->getUnitOfWork()->size() > 10000) {
                $this->entityManager->flush();
            }

            // Process the next page if the API provides a link to it.
            $metadata = $items->collection_info;
            if (isset($metadata->next_page_url)) {
                $next_page = $metadata->current_page_number + 1;
                $config['queryParameters']['page'] = $next_page;
                continue;
            }

            break;
        }

        $this->entityManager->flush();
        return $stats;
    }

    /**
     * Query the MVault API and return the result.
     *
     * @param string $url
     *   API URL to query.
     * @param array $config
     *   Query configuration options.
     *
     * @return array
     */
    private function query($url, array $config) {
        $response = $this->client->get($url, [
            'query' => $config['queryParameters'],
        ]);
        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getStatusCode());
        }
        $json = json_decode($response->getBody());
        if (isset($config['dataKey'])) {
            if (!isset($json->{$config['dataKey']})) {
                throw new BadRequestHttpException('Configured data key 
                not found in response.');
            }
            else {
                $items = $json->{$config['dataKey']};
            }
        }
        else {
            $items = $json;
        }
        return $items;
    }

    /**
     * Process items returned from a query.
     *
     * @param $entityClass
     *   The entity to process items for.
     * @param ArrayCollection $entities
     *   All existing entities for the class.
     * @param array $items
     *   Items returns from the query.
     * @param array $stats
     *   Array of data for tracking number of actions.
     */
    private function processItems($entityClass, ArrayCollection &$entities, array $items, array &$stats) {
        foreach ($items as $item) {
            $criteria = new Criteria(new Comparison(
                'id',
                '=',
                $item->membership_id
            ));
            /** @var Membership $entity */
            $entity = $entities->matching($criteria)->first();

            if ($entity) {
                if (isset($item->update_date)) {
                    $entity_updated = $entity->getUpdateDate();
                    $record_updated = $this->apiValueProcessor::processDateTimeString(
                        $item->update_date
                    );

                    // If the record update date is not greater than the entity
                    // updated date, do not continue with the update process.
                    if ($record_updated && $entity_updated
                        && $record_updated->format('Y-m-d H:i:s') <= $entity_updated->format('Y-m-d H:i:s')) {
                        $stats['noop']++;
                        continue;
                    }
                }
                $op = 'update';
            }
            else {
                $entity = new $entityClass;
                $this->propertyAccessor->setValue($entity, 'id', $item->membership_id);
                $op = 'add';
            }

            // Iterate and update all entity attributes from the API record.
            foreach ($item as $field_name => $value) {

                // Membership ID is set as the instance ID above.
                if ($field_name == 'membership_id') {
                    continue;
                }
                // Ignore the "current state" info.
                elseif ($field_name == 'current_state') {
                    continue;
                }

                $this->apiValueProcessor->process(
                    $entity,
                    $field_name,
                    $value
                );
            }

            // Merge changes to the entity.
            $this->entityManager->merge($entity);
            $stats[$op]++;
        }
    }
}
