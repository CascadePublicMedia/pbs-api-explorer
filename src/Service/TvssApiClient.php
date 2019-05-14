<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Headend;
use CascadePublicMedia\PbsApiExplorer\Entity\ScheduleProgram;
use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TvssApiClient
 *
 * @package CascadePublicMedia\PbsApiExplorer\Service
 */
class TvssApiClient extends PbsApiClientBase
{
    /**
     * @var array
     */
    protected $requiredSettings = [
        'tvss_base_uri' => 'Endpoint',
        'tvss_call_sign' => 'Call Sign',
        'tvss_api_key' => 'API Key',
    ];

    /**
     * TvssApiClient constructor.
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
                ->findByIdPrefix('tvss');

            $this->createClient([
                'base_uri' => $settings['tvss_base_uri']->getValue(),
                'headers' => [
                    'X-PBSAUTH' => $settings['tvss_api_key']->getValue()
                ],
            ]);
        }
    }

    /**
     * Update Headends information from the TVSS API.
     *
     * @return array
     *   Stats information from the update() method.
     *
     * @see TvssApiClient::update()
     */
    public function updateHeadends() {
        // Remove all existing Headend instances.
        $this->entityManager
            ->createQuery('delete from ' . Headend::class)
            ->execute();
        $this->entityManager->flush();
        $this->entityManager->clear();

        $config = self::createQueryConfig(['dataKey' => 'headends']);
        $items = $this->query(
            $this->getSetting('tvss_call_sign') . '/channels',
            $config
        );
        $stats = $this->processItems(Headend::class, $items, $config);
        $this->entityManager->flush();
        return $stats;
    }

    /**
     * Update Programs information from the TVSS API.
     *
     * @return array
     *   Stats information from the update() method.
     *
     * @see TvssApiClient::update()
     */
    public function updatePrograms() {
        $this->entityManager
            ->createQuery('delete from ' . ScheduleProgram::class)
            ->execute();
        $this->entityManager->flush();
        $this->entityManager->clear();

        $config = self::createQueryConfig(['dataKey' => 'programs']);
        $items = $this->query(ScheduleProgram::ENDPOINT, $config);
        $stats = $this->processItems(ScheduleProgram::class, $items, $config);
        $this->entityManager->flush();
        return $stats;
    }

    /**
     * Query the TVSS API and return the result.
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
     * Process items returned from a TVSS API query.
     *
     * @param $entityClass
     *   The entity to process items for.
     * @param $items
     *   Items returns from the query.
     * @param $config
     *   Query config options.
     *
     * @return array
     *   Stats about add/update/noop entities (all "update" in this case).
     */
    private function processItems($entityClass, $items, $config) {
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];

        // The TVSS API does not provide "last updated" information for any of
        // its items and object comparision will be inefficient in most
        // situations. All sync operations assume new records will be inserted.
        foreach ($items as $item) {
            $entity = new $entityClass;
            $this->propertyAccessor->setValue($entity, 'id', $item->cid);

            // Add any supplied extra properties.
            foreach ($config['extraProps'] as $property => $value) {
                $this->propertyAccessor->setValue(
                    $entity,
                    $property,
                    $value
                );
            }

            // Iterate and update all entity attributes from the API record.
            foreach ($item as $field_name => $value) {

                // The TVSS API provides the item ID at the same level as the
                // item attributes. The ID is handled above, so it is ignored
                // during processing here.
                if ($field_name == 'cid') {
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
            $stats['update']++;
        }

        return $stats;
    }
}
