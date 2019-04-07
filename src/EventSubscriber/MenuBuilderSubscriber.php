<?php

namespace CascadePublicMedia\PbsApiExplorer\EventSubscriber;

use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Event\ThemeEvents;
use KevinPapst\AdminLTEBundle\Model\MenuItemInterface;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder configures the main navigation.
 */
class MenuBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;

    /**
     * @param AuthorizationCheckerInterface $security
     */
    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ThemeEvents::THEME_SIDEBAR_SETUP_MENU => ['onSetupNavbar', 100],
            ThemeEvents::THEME_BREADCRUMB => ['onSetupNavbar', 100],
        ];
    }

    /**
     * Generate the main menu.
     *
     * @param SidebarMenuEvent $event
     */
    public function onSetupNavbar(SidebarMenuEvent $event)
    {
        $event->addItem(
            new MenuItemModel('home', 'Home', 'home', [], 'fas fa-tachometer-alt')
        );

        $media_manager = new MenuItemModel('media_manager', 'Media Manager', 'media_manager', [], 'fas fa-video');
        $media_manager->addChild(new MenuItemModel('media_manager_genres', 'Genres', 'media_manager_genres', [], 'fas fa-list'));
        $media_manager->addChild(new MenuItemModel('media_manager_franchises', 'Franchises', 'media_manager_franchises', [], 'fas fa-list'));
        $media_manager->addChild(new MenuItemModel('media_manager_shows', 'Shows', 'media_manager_shows', [], 'fas fa-list'));
        $event->addItem($media_manager);

        $station_manager = new MenuItemModel('station_manager', 'Station Manager', 'station_manager', [], 'fas fa-broadcast-tower');
        $station_manager->addChild(new MenuItemModel('station_manager_stations', 'Stations', 'station_manager_stations', [], 'fas fa-list'));
        $station_manager->addChild(new MenuItemModel('station_manager_stations_public', 'Stations (public)', 'station_manager_stations_public', [], 'fas fa-list'));
        $event->addItem($station_manager);

        $this->activateByRoute(
            $event->getRequest()->get('_route'),
            $event->getItems()
        );
    }

    /**
     * @param string $route
     * @param MenuItemModel[] $items
     */
    protected function activateByRoute($route, $items)
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() == $route) {
                $item->setIsActive(true);
            }
        }
    }
}
