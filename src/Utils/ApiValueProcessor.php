<?php

namespace CascadePublicMedia\PbsApiExplorer\Utils;

use CascadePublicMedia\PbsApiExplorer\Entity\Asset;
use CascadePublicMedia\PbsApiExplorer\Entity\AssetAvailability;
use CascadePublicMedia\PbsApiExplorer\Entity\AssetTag;
use CascadePublicMedia\PbsApiExplorer\Entity\Audience;
use CascadePublicMedia\PbsApiExplorer\Entity\Franchise;
use CascadePublicMedia\PbsApiExplorer\Entity\Genre;
use CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityCountry;
use CascadePublicMedia\PbsApiExplorer\Entity\GeoAvailabilityProfile;
use CascadePublicMedia\PbsApiExplorer\Entity\Image;
use CascadePublicMedia\PbsApiExplorer\Entity\Platform;
use CascadePublicMedia\PbsApiExplorer\Entity\Season;
use CascadePublicMedia\PbsApiExplorer\Entity\Station;
use CascadePublicMedia\PbsApiExplorer\Entity\Topic;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


class ApiValueProcessor
{
    /**
     * Media Manager uses two different date formats seemingly interchangeably.
     */
    private const MEDIA_MANAGER_API_DATE_FORMAT = 'Y-m-d\TH:i:s.u\Z';
    private const MEDIA_MANAGER_API_DATE_FORMAT_ALT = 'Y-m-d\TH:i:s\Z';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FieldMapper
     */
    private $fieldMapper;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * ApiValueProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldMapper $fieldMapper
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(EntityManagerInterface $entityManager,
                                FieldMapper $fieldMapper,
                                PropertyAccessorInterface $propertyAccessor)
    {
        $this->entityManager = $entityManager;
        $this->fieldMapper = $fieldMapper;
        $this->propertyAccessor = $propertyAccessor;
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
               $datetime = DateTimeImmutable::createFromFormat(
                   self::MEDIA_MANAGER_API_DATE_FORMAT,
                   $apiFieldValue
               );
               if ($datetime === FALSE) {
                   $datetime = DateTimeImmutable::createFromFormat(
                       self::MEDIA_MANAGER_API_DATE_FORMAT_ALT,
                       $apiFieldValue
                   );
               }
               $apiFieldValue = $datetime;
               break;
           case 'end':
           case 'start':
           case 'updated_at':
               $datetime = DateTime::createFromFormat(
                   self::MEDIA_MANAGER_API_DATE_FORMAT,
                   $apiFieldValue
               );
               if ($datetime === FALSE) {
                   $datetime = DateTime::createFromFormat(
                       self::MEDIA_MANAGER_API_DATE_FORMAT_ALT,
                       $apiFieldValue
                   );
               }
               $apiFieldValue = $datetime;
               break;
           case 'encored_on':
           case 'premiered_on':
               $apiFieldValue = DateTime::createFromFormat('Y-m-d', $apiFieldValue);
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
            case 'assets':

                // Determine the entity type these assets are associated with.
                try {
                    $reflect = new \ReflectionClass($entity);
                    $entity_type = strtolower($reflect->getShortName());
                }
                catch (\ReflectionException $e) {
                    throw new \RuntimeException('Unknown entity type.');
                }

                foreach ($apiFieldValue as $item) {
                    /** @var Asset $asset */
                    $asset = $this->entityManager
                        ->getRepository(Asset::class)
                        ->find($item->id);

                    if (!$asset) {
                        $asset = new Asset();
                        $asset->setId($item->id);
                    }
                    else {
                        $updated = $this->processValue(
                            'updated_at',
                            $item->attributes->updated_at
                        );
                        if ($asset->getUpdated() >= $updated) {
                            continue;
                        }
                    }

                    // Associate asset with the entity.
                    $this->propertyAccessor->setValue(
                        $asset,
                        $entity_type,
                        $entity
                    );

                    foreach ($item->attributes as $field_name => $value) {
                        // TODO: Refactor. This loop is used in multiple places.
                        // The "tags" field may be NULL, so it does not get
                        // picked up automatically as an array.
                        if (is_array($value) || $field_name == 'tags') {
                            $this->processArray($asset, $field_name, $value);
                        }
                        elseif (is_object($value)) {
                            $this->processObject($asset, $field_name, $value);
                        }
                        else {
                            $this->propertyAccessor->setValue(
                                $asset,
                                $this->fieldMapper->map($field_name),
                                $this->processValue($field_name, $value)
                            );
                        }
                    }

                    $this->entityManager->merge($asset);
                }
                break;
            case 'audience':
                foreach ($apiFieldValue as $value) {
                    $station = NULL;
                    if (!is_null($value->station)) {
                        /** @var Station $station */
                        $station = $this->entityManager
                            ->getRepository(Station::class)
                            ->find($value->station->id);
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
                        // TODO: Remove this flush?
                        $this->entityManager->flush();
                    }

                    $entity->addAudience($audience);
                }
                break;
            case 'captions':
            case 'chapters':
                $this->propertyAccessor->setValue(
                    $entity,
                    $this->fieldMapper->map($apiFieldName),
                    $this->processValue($apiFieldName, $apiFieldValue)
                );
                break;
            case 'collections':
                // TODO
                break;
            case 'countries':
                /** @var ArrayCollection $countries */
                $countries = $this->entityManager
                    ->getRepository(GeoAvailabilityCountry::class)
                    ->findAll();
                $countries = new ArrayCollection($countries);

                foreach ($apiFieldValue as $value) {
                    $criteria = new Criteria(new Comparison(
                        'id',
                        '=',
                        $value->id
                    ));

                    /** @var GeoAvailabilityCountry $country */
                    $country = $countries->matching($criteria)->first();

                    if (!$country) {
                        $country = new GeoAvailabilityCountry();
                        $country->setId($value->id);
                    }

                    $country->setName($value->name);
                    $country->setCode($value->code);
                    $country->setUpdated($this->processValue(
                        'updated_at',
                        $value->updated_at
                    ));
                    $this->entityManager->persist($country);

                    $entity->addCountry($country);

                }
                break;
            case 'images':
                // Determine the entity type these images are associated with.
                try {
                    $reflect = new \ReflectionClass($entity);
                    $entity_type = strtolower($reflect->getShortName());
                }
                catch (\ReflectionException $e) {
                    throw new \RuntimeException('Unknown entity type.');
                }

                /** @var ArrayCollection $images */
                $images = $entity->getImages();

                foreach ($apiFieldValue as $item) {
                    $updated = NULL;
                    if (isset($item->updated_at)) {
                        $updated = $this->processValue(
                            'updated_at',
                            $item->updated_at
                        );
                    }

                    $criteria = new Criteria(new Comparison(
                        'profile',
                        '=',
                        $item->profile
                    ));

                    /** @var Image $image */
                    $image = $images->matching($criteria)->first();

                    if (!$image) {
                        $image = new Image();
                        $this->propertyAccessor->setValue(
                            $image,
                            $entity_type,
                            $entity
                        );
                    }
                    elseif ($updated && $image->getUpdated() >= $updated) {
                        continue;
                    }

                    $image->setProfile($item->profile);

                    // Image keys of "url" and "image" as used interchangeably.
                    if (isset($item->image)) {
                        $image->setImage($item->image);
                    }
                    elseif (isset($item->url)) {
                        $image->setImage($item->url);
                    }

                    if ($updated) {
                        $image->setUpdated($updated);
                    }

                    $this->entityManager->merge($image);
                }

                //$entity->setImages($apiFieldValue);
                break;
            case 'links':
            case 'related_links':
                $entity->setLinks($apiFieldValue);
                break;
            case 'platforms':
                foreach ($apiFieldValue as $value) {
                    $platform = $this->entityManager
                        ->getRepository(Platform::class)
                        ->find($value->id);
                    if ($platform) {
                        $entity->addPlatform($platform);
                    }
                }
                break;
            case 'related_promos':
                // TODO
                break;
            case 'seasons':
                foreach ($apiFieldValue as $value) {
                    $season = NULL;

                    /** @var Season $station */
                    $season = $this->entityManager
                        ->getRepository(Season::class)
                        ->find($value->id);

                    if (!$season) {
                        $season = new Season();
                        $season->setId($value->id);
                    }

                    $season->setOrdinal($value->attributes->ordinal);
                    $season->setTitle($value->attributes->title);
                    $season->setTitleSortable($value->attributes->title_sortable);
                    $season->setUpdated($this->processValue(
                        'updated_at',
                        $value->attributes->updated_at
                    ));
                    $this->entityManager->persist($season);

                    $entity->addSeason($season);
                }
                break;
            case 'specials':
                // TODO
                break;
            case 'tags':
                if (is_null($apiFieldValue)) {
                    $apiFieldValue = [];
                }

                foreach ($apiFieldValue as $value) {
                    $tag = $this->entityManager
                        ->getRepository(AssetTag::class)
                        ->find($value);

                    if (!$tag) {
                        $tag = new AssetTag();
                        $tag->setId($value);
                        $this->entityManager->persist($tag);
                    }

                    $entity->addTag($tag);
                }
                break;
            case 'topics':
                // TODO
                break;
        }
    }

    public function processObject(&$entity, $apiFieldName, $apiFieldValue) {
        $updatedFieldValue = NULL;

        switch ($apiFieldName) {
            case 'availabilities':
                /** @var AssetAvailability[] $availabilities */
                $availabilities = $this->entityManager
                    ->getRepository(AssetAvailability::class)
                    ->findAllByAssetIndexedByType($entity);
                foreach ($apiFieldValue as $type => $constraints) {
                    $updated = $this->processValue(
                        'updated_at',
                        $constraints->updated_at
                    );

                    if (isset($availabilities[$type])) {
                        $availability = $availabilities[$type];
                        if ($availability->getUpdated() >= $updated) {
                            continue;
                        }
                    }
                    else {
                        $availability = new AssetAvailability();
                        $availability->setType($type);
                    }

                    $availability->setStartDateTime($this->processValue(
                        'start',
                        $constraints->start
                    ));
                    $availability->setEndDateTime($this->processValue(
                        'end',
                        $constraints->end
                    ));
                    $availability->setUpdated($updated);
                    $availability->setAsset($entity);
                    $this->entityManager->merge($availability);
                }
                break;
            case 'full_length_asset':
                // TODO
                $updatedFieldValue = NULL;
                break;
            case 'franchise':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Franchise::class)
                    ->find($apiFieldValue->id);
                break;
            case 'genre':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Genre::class)
                    ->find($apiFieldValue->id);
                break;
            case 'geo_profile':
                /** @var ArrayCollection $profiles */
                $profiles = $this->entityManager
                    ->getRepository(GeoAvailabilityProfile::class)
                    ->findAll();
                $profiles = new ArrayCollection($profiles);

                $criteria = new Criteria(new Comparison(
                    'id',
                    '=',
                    $apiFieldValue->id
                ));

                /** @var GeoAvailabilityProfile $profile */
                $profile = $profiles->matching($criteria)->first();

                if (!$profile) {
                    $profile = new GeoAvailabilityProfile();
                    $profile->setId($apiFieldValue->id);
                }

                $profile->setName($apiFieldValue->name);
                $profile->setUpdated($this->processValue(
                    'updated_at',
                    $apiFieldValue->updated_at
                ));
                $this->entityManager->persist($profile);

                $entity->setGeoProfile($profile);
                break;
            case 'station':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Station::class)
                    ->find($apiFieldValue->id);
                break;
            case 'videos':
                $updatedFieldValue = (array) $apiFieldValue;
                break;
        }

        // Handle objects that need specific value updates.
        if ($updatedFieldValue) {
            $this->propertyAccessor->setValue(
                $entity,
                $this->fieldMapper->map($apiFieldName),
                $this->processValue($apiFieldName, $updatedFieldValue)
            );
        }
    }
}
