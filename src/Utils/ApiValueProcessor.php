<?php

namespace CascadePublicMedia\PbsApiExplorer\Utils;

use CascadePublicMedia\PbsApiExplorer\Entity\Audience;
use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\Platform;
use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ApiValueProcessor
{
    /**
     * Standard date format for Media Manager API values.
     */
    private const MEDIA_MANAGER_API_DATE_FORMAT = 'Y-m-d\TH:i:s.u\Z';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiValueProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $apiFieldName
     * @param $apiFieldValue
     * @return mixed
     */
    public function processValue($apiFieldName, $apiFieldValue) {

        // Always return NULL values directly.
        if (is_null($apiFieldValue)) {
            return $apiFieldValue;
        }

       switch ($apiFieldName) {
           case 'created_at':
               $apiFieldValue = DateTimeImmutable::createFromFormat(
                   self::MEDIA_MANAGER_API_DATE_FORMAT,
                   $apiFieldValue
               );
               break;
           case 'franchise':
               $apiFieldValue = $this->entityManager
                   ->getRepository(Franchise::class)
                   ->find($apiFieldValue->id);
               break;
           case 'genre':
               $apiFieldValue = $this->entityManager
                   ->getRepository(Genre::class)
                   ->find($apiFieldValue->id);
               break;
           case 'premiered_on':
               $apiFieldValue = DateTime::createFromFormat('Y-m-d', $apiFieldValue);
               break;
           case 'station':
               $apiFieldValue = $this->entityManager
                   ->getRepository(Station::class)
                   ->find($apiFieldValue->id);
               break;
           case 'updated_at':
               $apiFieldValue = DateTime::createFromFormat(
                   self::MEDIA_MANAGER_API_DATE_FORMAT,
                   $apiFieldValue
               );
               break;
       }

       return $apiFieldValue;
    }

    /**
     * @param $entity
     * @param $apiFieldName
     * @param $apiFieldValue
     */
    public function processArray(&$entity, $apiFieldName, $apiFieldValue) {
        switch ($apiFieldName) {
            case 'audience':
                foreach ($apiFieldValue as $key => $value) {
                    $station = NULL;
                    if (!is_null($value->station)) {
                        /** @var Station $station */
                        $station = $this->entityManager
                            ->getRepository(Station::class)
                            ->find($value->station->id);
                        $criteria['station'] = $station;
                    }

                    $audience = $this->entityManager
                        ->getRepository(Audience::class)
                        ->findOneBy([
                            'scope' => $value->scope,
                            'station' => $station,
                        ]);

                    if (!$audience) {
                        $audience = new Audience();
                        $audience->setScope($value->scope);
                        $audience->setStation($station);
                        $this->entityManager->persist($audience);
                        $this->entityManager->flush();
                    }

                    $entity->addAudience($audience);
                }
                break;
            case 'images':
                $entity->setImages($apiFieldValue);
                break;
            case 'links':
                $entity->setLinks($apiFieldValue);
                break;
            case 'platforms':
                foreach ($apiFieldValue as $key => $value) {
                    $platform = $this->entityManager
                        ->getRepository(Platform::class)
                        ->find($value->id);
                    if ($platform) {
                        $entity->addPlatform($platform);
                    }
                }
                break;
        }
    }
}
