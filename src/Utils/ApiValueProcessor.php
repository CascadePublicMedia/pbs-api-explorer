<?php

namespace CascadePublicMedia\PbsApiExplorer\Utils;

use CascadePublicMedia\PbsApiExplorer\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


/**
 * Class ApiValueProcessor
 *
 * TODO: BLOW THIS UP. Refactor on field-specific classes/methods/etc.
 *
 * @package CascadePublicMedia\PbsApiExplorer\Utils
 */
class ApiValueProcessor
{
    /**
     * Media Manager uses two different date formats seemingly interchangeably.
     */
    public const MEDIA_MANAGER_API_DATE_FORMAT = 'Y-m-d\TH:i:s.u\Z';
    public const MEDIA_MANAGER_API_DATE_FORMAT_ALT = 'Y-m-d\TH:i:s\Z';

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
     * @param $entity
     * @param $apiFieldName
     * @param $apiFieldValue
     */
    public function process(&$entity, $apiFieldName, $apiFieldValue) {
        // The "tags" field may be NULL, so it does not get
        // picked up automatically as an array.
        if (is_array($apiFieldValue) || $apiFieldName == 'tags') {
            $this->processArray($entity, $apiFieldName, $apiFieldValue);
        }
        elseif (is_object($apiFieldValue)) {
            $this->processObject($entity, $apiFieldName, $apiFieldValue);
        }
        else {
            $this->processString($entity, $apiFieldName, $apiFieldValue);
        }
    }

    /**
     * @param object $entity
     * @param string $apiFieldName
     * @param array $apiFieldValue
     */
    private function processArray(&$entity, $apiFieldName, $apiFieldValue) {
        switch ($apiFieldName) {
            case 'assets':

                // Determine the entity type these assets are associated with.
                try {
                    $reflect = new ReflectionClass($entity);
                    $entity_type = strtolower($reflect->getShortName());
                }
                catch (ReflectionException $e) {
                    throw new RuntimeException('Unknown entity type.');
                }

                foreach ($apiFieldValue as $item) {
                    /** @var Entity\Asset $asset */
                    $asset = $this->entityManager
                        ->getRepository(Entity\Asset::class)
                        ->find($item->id);

                    if (!$asset) {
                        $asset = new Entity\Asset();
                        $asset->setId($item->id);
                    }
                    else {
                        $updated = self::processDateTimeString(
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
                        $this->process($asset, $field_name, $value);
                    }

                    $this->entityManager->merge($asset);
                }
                break;
            case 'audience':
                foreach ($apiFieldValue as $value) {
                    $station = NULL;
                    if (!is_null($value->station)) {
                        /** @var Entity\Station $station */
                        $station = $this->entityManager
                            ->getRepository(Entity\Station::class)
                            ->find($value->station->id);
                    }

                    $audience = $this->entityManager
                        ->getRepository(Entity\Audience::class)
                        ->findOneBy([
                            'scope' => $value->scope,
                            'station' => $station,
                        ]);

                    if (!$audience) {
                        $audience = new Entity\Audience();
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
                $this->processString($entity, $apiFieldName, $apiFieldValue);
                break;
            case 'collections':
                // TODO
                break;
            case 'countries':
                /** @var ArrayCollection $countries */
                $countries = $this->entityManager
                    ->getRepository(Entity\GeoAvailabilityCountry::class)
                    ->findAll();
                $countries = new ArrayCollection($countries);

                foreach ($apiFieldValue as $value) {
                    $criteria = new Criteria(new Comparison(
                        'id',
                        '=',
                        $value->id
                    ));

                    /** @var Entity\GeoAvailabilityCountry $country */
                    $country = $countries->matching($criteria)->first();

                    if (!$country) {
                        $country = new Entity\GeoAvailabilityCountry();
                        $country->setId($value->id);
                    }

                    $country->setName($value->name);
                    $country->setCode($value->code);
                    $this->processString(
                        $country,
                        'updated_at',
                        $value->updated_at
                    );
                    $this->entityManager->persist($country);

                    $entity->addCountry($country);

                }
                break;
            case 'feeds':
                // Headend feeds are treated as a simple array because they have
                // an added property, "cable_number", that does not conform to
                // the standard Feed entity type.
                $entity->setFeeds($apiFieldValue);
                break;
            case 'images':
                // Determine the entity type these images are associated with.
                try {
                    $reflect = new ReflectionClass($entity);
                    $entity_type = strtolower($reflect->getShortName());
                }
                catch (ReflectionException $e) {
                    throw new RuntimeException('Unknown entity type.');
                }

                /** @var ArrayCollection $images */
                $images = $entity->getImages();

                foreach ($apiFieldValue as $item) {
                    $updated = NULL;
                    if (isset($item->updated_at)) {
                        $updated = self::processDateTimeString(
                            $item->updated_at
                        );
                    }

                    $criteria = new Criteria(new Comparison(
                        'profile',
                        '=',
                        $item->profile
                    ));

                    /** @var Entity\Image $image */
                    $image = $images->matching($criteria)->first();

                    if (!$image) {
                        $image = new Entity\Image();
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
                break;
            case 'links':
            case 'related_links':
                $entity->setLinks($apiFieldValue);
                break;
            case 'platforms':
                foreach ($apiFieldValue as $value) {
                    $platform = $this->entityManager
                        ->getRepository(Entity\Platform::class)
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

                    /** @var Entity\Season $station */
                    $season = $this->entityManager
                        ->getRepository(Entity\Season::class)
                        ->find($value->id);

                    if (!$season) {
                        $season = new Entity\Season();
                        $season->setId($value->id);
                    }

                    $season->setOrdinal($value->attributes->ordinal);
                    $season->setTitle($value->attributes->title);
                    $season->setTitleSortable($value->attributes->title_sortable);
                    $this->processString(
                        $season,
                        'updated_at',
                        $value->attributes->updated_at
                    );
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
                        ->getRepository(Entity\AssetTag::class)
                        ->find($value);

                    if (!$tag) {
                        $tag = new Entity\AssetTag();
                        $tag->setId($value);
                        $this->entityManager->persist($tag);
                    }

                    $entity->addTag($tag);
                }
                break;
            case 'topics':
                // TODO
                break;
            case 'updated_fields':
                $entity->setUpdatedFields($apiFieldValue);
                break;
        }
    }

    /**
     * @param object $entity
     * @param string $apiFieldName
     * @param object $apiFieldValue
     */
    private function processObject(&$entity, $apiFieldName, $apiFieldValue) {
        $updatedFieldValue = NULL;

        switch ($apiFieldName) {
            case 'availabilities':
                /** @var Entity\AssetAvailability[] $availabilities */
                $availabilities = $this->entityManager
                    ->getRepository(Entity\AssetAvailability::class)
                    ->findAllByAssetIndexedByType($entity);
                foreach ($apiFieldValue as $type => $constraints) {
                    $updated = self::processDateTimeString($constraints->updated_at);

                    if (isset($availabilities[$type])) {
                        $availability = $availabilities[$type];
                        if ($availability->getUpdated() >= $updated) {
                            continue;
                        }
                    }
                    else {
                        $availability = new Entity\AssetAvailability();
                        $availability->setType($type);
                    }

                    $this->processString(
                        $availability,
                        'start',
                        $constraints->start
                    );
                    $this->processString(
                        $availability,
                        'end',
                        $constraints->end
                    );
                    $availability->setUpdated($updated);
                    $availability->setAsset($entity);
                    $this->entityManager->merge($availability);
                }
                break;
            case 'current_state':
                $updatedFieldValue = (array) $apiFieldValue;
                break;
            case 'full_length_asset':
                // TODO
                $updatedFieldValue = NULL;
                break;
            case 'franchise':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Entity\Franchise::class)
                    ->find($apiFieldValue->id);
                break;
            case 'genre':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Entity\Genre::class)
                    ->find($apiFieldValue->id);
                break;
            case 'geo_profile':
                /** @var ArrayCollection $profiles */
                $profiles = $this->entityManager
                    ->getRepository(Entity\GeoAvailabilityProfile::class)
                    ->findAll();
                $profiles = new ArrayCollection($profiles);

                $criteria = new Criteria(new Comparison(
                    'id',
                    '=',
                    $apiFieldValue->id
                ));

                /** @var Entity\GeoAvailabilityProfile $profile */
                $profile = $profiles->matching($criteria)->first();

                if (!$profile) {
                    $profile = new Entity\GeoAvailabilityProfile();
                    $profile->setId($apiFieldValue->id);
                }

                $profile->setName($apiFieldValue->name);
                $this->processString(
                    $profile,
                    'updated_at',
                    $apiFieldValue->updated_at
                );
                $this->entityManager->persist($profile);

                $entity->setGeoProfile($profile);
                break;
            case 'pbs_profile':
                /** @var ArrayCollection $profiles */
                $profiles = $this->entityManager
                    ->getRepository(Entity\PbsProfile::class)
                    ->findAll();
                $profiles = new ArrayCollection($profiles);

                $criteria = new Criteria(new Comparison(
                    'id',
                    '=',
                    $apiFieldValue->UID
                ));

                /** @var Entity\PbsProfile $profile */
                $profile = $profiles->matching($criteria)->first();

                if (!$profile) {
                    $profile = new Entity\PbsProfile();
                    $profile->setId($apiFieldValue->UID);
                }

                // The PBS Profile retrieval will occasionally fail, but the
                // "retrieval_status" should report this.
                if ($apiFieldValue->retrieval_status->status === 200) {
                    $profile->setFirstName($apiFieldValue->first_name);
                    $profile->setLastName($apiFieldValue->last_name);
                    if (!is_null($apiFieldValue->birth_date)) {
                        $profile->setBirthDate(
                            DateTime::createFromFormat('Y-m-d', $apiFieldValue->birth_date)
                        );
                    }
                    else {
                        $profile->setBirthDate($apiFieldValue->birth_date);
                    }
                    $profile->setEmail($apiFieldValue->email);
                    $profile->setLoginProvider($apiFieldValue->login_provider);
                }

                $this->entityManager->merge($profile);

                $entity->setPbsProfile($profile);
                break;
            case 'station':
                $updatedFieldValue = $this->entityManager
                    ->getRepository(Entity\Station::class)
                    ->find($apiFieldValue->id);
                break;
            case 'videos':
                $updatedFieldValue = (array) $apiFieldValue;
                break;
        }

        // Handle objects that need specific value updates.
        if ($updatedFieldValue) {
            $this->processString($entity, $apiFieldName, $updatedFieldValue);
        }
    }

    /**
     * @param object $entity
     * @param $apiFieldName
     * @param $apiFieldValue
     */
    private function processString(&$entity, $apiFieldName, $apiFieldValue) {
        if (is_null($apiFieldValue)) {
            $updatedFieldValue = NULL;
        }
        else {
            switch ($apiFieldName) {
                case 'airing_type':
                    $apiFieldName = 'taped';
                    if ($apiFieldValue == 'Taped') {
                        $updatedFieldValue = TRUE;
                    }
                    else {
                        $updatedFieldValue = FALSE;
                    }
                    break;
                case 'activation_date':
                case 'create_date':
                case 'created_at':
                case 'end':
                case 'expire_date':
                case 'grace_period':
                case 'start':
                case 'start_date':
                case 'timestamp':
                case 'update_date':
                case 'updated_at':
                    $updatedFieldValue = self::processDateTimeString($apiFieldValue);
                    break;
                case 'birth_date':
                case 'encored_on':
                case 'premiered_on':
                    $updatedFieldValue = DateTime::createFromFormat(
                        'Y-m-d',
                        $apiFieldValue
                    );
                    break;
                case 'program_id':
                    // Determine the entity type being processed.
                    try {
                        $reflect = new ReflectionClass($entity);
                        $entity_type = $reflect->getName();
                    }
                    catch (ReflectionException $e) {
                        throw new RuntimeException('Unknown entity type.');
                    }

                    // ScheduleProgram: pass through as string.
                    if ($entity_type == Entity\ScheduleProgram::class) {
                        $updatedFieldValue = $apiFieldValue;
                    }
                    // Listing: associate with a ScheduleProgram instance.
                    elseif ($entity_type == Entity\Listing::class) {
                        $program = $this->entityManager
                            ->getRepository(Entity\ScheduleProgram::class)
                            ->findOneByProgramId($apiFieldValue);
                        // TODO: Create new program if not found.
                        if ($program) {
                            $entity->setProgram($program);
                        }
                    }
                    break;
                default:
                    $updatedFieldValue = $apiFieldValue;
            }
        }

        if (isset($updatedFieldValue)) {
            $this->propertyAccessor->setValue(
                $entity,
                $this->fieldMapper->map($apiFieldName),
                $updatedFieldValue
            );
        }
    }

    /**
     * @param $string
     * @return DateTime
     */
    public static function processDateTimeString($string) {
        $datetime = DateTime::createFromFormat(
            self::MEDIA_MANAGER_API_DATE_FORMAT,
            $string
        );
        if ($datetime === FALSE) {
            $datetime = DateTime::createFromFormat(
                self::MEDIA_MANAGER_API_DATE_FORMAT_ALT,
                $string
            );
        }
        return $datetime;
    }

}
