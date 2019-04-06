<?php

namespace CascadePublicMedia\PbsApiExplorer\DataFixtures;

use CascadePublicMedia\PbsApiExplorer\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Class MediaManagerApiFixtures
 *
 * @package CascadePublicMedia\PbsApiExplorer\DataFixtures
 */
class MediaManagerApiFixtures extends Fixture
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * Consistent date with no public endpoints on the Media Manager API.
     */
    private const FIXTURES = [

        /**
         * @see https://docs.pbs.org/display/CDA/Franchises#Franchises-Platformlist
         */
        Platform::class => [
            [
                'id' => 'e5a289b8-01b3-41ce-92c1-3eb5a4e14bc0',
                'slug' => 'allplatforms',
                'title' => 'All Platforms',
            ],
            [
                'id' => '857b7813-d748-4d70-a5c5-a826cb72e5a4',
                'slug' => 'appletv',
                'title' => 'Apple TV',
            ],
            [
                'id' => '22f85aa7-cffc-462f-bd65-14218960ac5c',
                'slug' => 'partnerplayer',
                'title' => 'Partner Player',
            ],
            [
                'id' => '109d650b-ca9d-4d63-84b1-5231ccab7ff1',
                'slug' => 'bento',
                'title' => 'Bento',
            ],
            [
                'id' => 'f34e9bd6-ef03-48d9-a945-630e5eb009cb',
                'slug' => 'pbsorg',
                'title' => 'PBS.org (national) Video Portal',
            ],
            [
                'id' => '0ff7c8dc-8a23-4429-9e6e-38b808ebe828',
                'slug' => 'videoportal',
                'title' => 'Station Video Portal',
            ],
        ]

    ];

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::FIXTURES as $class => $fixtures) {
            foreach ($fixtures as $fixture) {
                $entity = new $class;
                foreach ($fixture as $key => $value) {
                    $this->propertyAccessor->setValue($entity, $key, $value);
                }
                $manager->persist($entity);
            }
        }
        $manager->flush();
    }
}
