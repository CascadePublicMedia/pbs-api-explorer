<?php

namespace CascadePublicMedia\PbsApiExplorer\DataTable\Type;

use CascadePublicMedia\PbsApiExplorer\Entity;
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
     * Create a linked string to a Genre page.
     *
     * @param $context
     *   Either a Genre entity or an entity with a getGenre() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Genre title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderGenreLink($context, $value)
    {
        /** @var Entity\Genre $entity */
        if (get_class($context) == Entity\Genre::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getGenre')) {
            $entity = $context->getGenre();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'media_manager_genres_genre',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a Topic page.
     *
     * @param $context
     *   Either a Topic entity or an entity with a getTopic() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Topic title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderTopicLink($context, $value)
    {
        /** @var Entity\Topic $entity */
        if (get_class($context) == Entity\Topic::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getTopic')) {
            $entity = $context->getTopic();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'media_manager_topics_topic',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a Franchise page.
     *
     * @param $context
     *   Either a Franchise entity or an entity with a getFranchise() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Franchise title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderFranchiseLink($context, $value)
    {
        /** @var Entity\Franchise $entity */
        if (get_class($context) == Entity\Franchise::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getFranchise')) {
            $entity = $context->getFranchise();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'media_manager_franchises_franchise',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
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
    protected function renderShowLink($context, $value)
    {
        /** @var Entity\Show $entity */
        if (get_class($context) == Entity\Show::class) {
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

    /**
     * Create a linked string to a Season page.
     *
     * @param $context
     *   Either a Season entity or an entity with a getSeason() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Season title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderSeasonLink($context, $value)
    {
        /** @var Entity\Season $entity */
        if (get_class($context) == Entity\Season::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getSeason')) {
            $entity = $context->getSeason();
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate(
            'media_manager_seasons_season',
            ['id' => $entity->getId()]
        );
        return sprintf('<a href="%s">%s</a>', $url, (string) $entity);
    }

    /**
     * Create a linked string to an Episode page.
     *
     * @param $context
     *   Either a Episode entity or an entity with a getEpisode() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Episode title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderEpisodeLink($context, $value)
    {
        /** @var Entity\Episode $entity */
        if (get_class($context) == Entity\Episode::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getEpisode')) {
            $entity = $context->getEpisode();
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate(
            'media_manager_episodes_episode',
            ['id' => $entity->getId()]
        );
        return sprintf('<a href="%s">%s</a>', $url, (string) $entity);
    }

    /**
     * Create a linked string to an Asset page.
     *
     * @param $context
     *   Either a Asset entity or an entity with a getAsset() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Asset title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderAssetLink($context, $value)
    {
        /** @var Entity\Episode $entity */
        if (get_class($context) == Entity\Asset::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getAsset')) {
            $entity = $context->getAsset();
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate(
            'media_manager_assets_asset',
            ['id' => $entity->getId()]
        );
        return sprintf('<a href="%s">%s</a>', $url, (string) $entity);
    }

    /**
     * Create a linked string to an Image page.
     *
     * @param $context
     *   Either a Image entity or an entity with a getImage() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Image title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderImageLink($context, $value)
    {
        /** @var Entity\Image $entity */
        if (get_class($context) == Entity\Image::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getImage')) {
            $entity = $context->getImage();
        }
        else {
            return 'Unknown';
        }
        $url = $this->router->generate(
            'media_manager_images_image',
            ['id' => $entity->getId()]
        );
        return sprintf('<a href="%s">%s</a>', $url, (string) $entity);
    }

    /**
     * Create a linked string to a Station page.
     *
     * @param $context
     *   Either a Station entity or an entity with a getStation() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Station title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderStationLink($context, $value)
    {
        /** @var Entity\Station $entity */
        if (get_class($context) == Entity\Station::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getStation')) {
            $entity = $context->getStation();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'station_manager_stations_station',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a ScheduleProgram page.
     *
     * @param $context
     *   Either a ScheduleProgram entity or an entity with a getProgram()
     *   method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the ScheduleProgram title linked to an entity view,
     *   otherwise 'Unknown'.
     */
    protected function renderProgramLink($context, $value)
    {
        /** @var Entity\Station $entity */
        if (get_class($context) == Entity\ScheduleProgram::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getProgram')) {
            $entity = $context->getProgram();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'tvss_programs_program',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a Headend page.
     *
     * @param $context
     *   Either a Headend entity or an entity with a getHeadend() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Headend title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderHeadendLink($context, $value)
    {
        /** @var Entity\Station $entity */
        if (get_class($context) == Entity\Headend::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getHeadend')) {
            $entity = $context->getHeadend();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'tvss_headends_headend',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a Listing page.
     *
     * @param $context
     *   Either a Listing entity or an entity with a getListing() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Listing title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderListingLink($context, $value)
    {
        /** @var Entity\Listing $entity */
        if (get_class($context) == Entity\Listing::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getListing')) {
            $entity = $context->getListing();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'tvss_listings_listing',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a PbsProfile page.
     *
     * @param $context
     *   Either a PbsProfile entity or an entity with a getPbsProfile() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the PbsProfile title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderPbProfileLink($context, $value)
    {
        /** @var Entity\PbsProfile $entity */
        if (get_class($context) == Entity\PbsProfile::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getPbsProfile')) {
            $entity = $context->getPbsProfile();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'mvault_profiles_profile',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a Membership page.
     *
     * @param $context
     *   Either a Membership entity or an entity with a getMembership() method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the Membership title linked to an entity view, otherwise
     *   'Unknown'.
     */
    protected function renderMembershipLink($context, $value)
    {
        /** @var Entity\Membership $entity */
        if (get_class($context) == Entity\Membership::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getMembership')) {
            $entity = $context->getMembership();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'mvault_memberships_membership',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }

    /**
     * Create a linked string to a ChangelogEntry page.
     *
     * @param $context
     *   Either a ChangelogEntry entity or an entity with a getChangelogEntry()
     *   method.
     * @param mixed $value
     *   A referenced piece of data from DataTables (unused).
     *
     * @return string
     *   A string with the ChangelogEntry title linked to an entity view,
     *   otherwise 'Unknown'.
     */
    protected function renderChangelogEntryLink($context, $value)
    {
        /** @var Entity\ChangelogEntry $entity */
        if (get_class($context) == Entity\ChangelogEntry::class) {
            $entity = $context;
        }
        elseif (method_exists($context, 'getChangelogEntry')) {
            $entity = $context->getChangelogEntry();
        }
        if (!$entity) {
            $str = '<em>None</em>';
        }
        else {
            $url = $this->router->generate(
                'media_manager_changelog_entry',
                ['id' => $entity->getId()]
            );
            $str = sprintf('<a href="%s">%s</a>', $url, (string) $entity);
        }
        return $str;
    }
}