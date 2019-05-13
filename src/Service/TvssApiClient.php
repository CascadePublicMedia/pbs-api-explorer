<?php

namespace CascadePublicMedia\PbsApiExplorer\Service;

use CascadePublicMedia\PbsApiExplorer\Entity\Setting;
use CascadePublicMedia\PbsApiExplorer\Utils\ApiValueProcessor;
use CascadePublicMedia\PbsApiExplorer\Utils\FieldMapper;
use Doctrine\Common\Collections\Collection;
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
     * {@inheritDoc}
     */
    public function update($entityClass, Collection $entities, $url, array $config)
    {
        $stats = ['add' => 0, 'update' => 0, 'noop' => 0];
        $config = self::createUpdateConfig($config);

        $response = $this->client->get($url, [
            'query' => $config['queryParameters'],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getStatusCode());
        }

        $json = json_decode($response->getBody());
        if (!isset($json->{$config['dataKey']})) {
            throw new BadRequestHttpException('Configured data key 
                not found in response.');
        }
        else {
            $items = $json->{$config['dataKey']};
        }

        // The TVSS API does not provide "last updated" information for any of
        // its items and object comparision will be inefficient in most
        // situations. All sync operations will fully remove and re-add data
        // from the API.
        $this->entityManager
            ->createQuery('delete from ' . $entityClass)
            ->execute();
        $this->entityManager->flush();
        $this->entityManager->clear();

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

    // Flush any changes.
    $this->entityManager->flush();

    return $stats;
    }
}
