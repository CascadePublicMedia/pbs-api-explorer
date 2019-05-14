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

        if ($this->security->isGranted('ROLE_USER')) {
            // Media Manager menu items.
            $media_manager = new MenuItemModel(
                'media_manager',
                'Media Manager',
                'media_manager',
                [],
                'fas fa-video'
            );
            $media_manager->addChild(new MenuItemModel(
                'media_manager_genres',
                'Genres',
                'media_manager_genres',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_assets',
                'Assets',
                'media_manager_assets',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_images',
                'Images',
                'media_manager_images',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_franchises',
                'Franchises',
                'media_manager_franchises',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_shows',
                'Shows',
                'media_manager_shows',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_seasons',
                'Seasons',
                'media_manager_seasons',
                [],
                'fas fa-list'
            ));
            $media_manager->addChild(new MenuItemModel(
                'media_manager_episodes',
                'Episodes',
                'media_manager_episodes',
                [],
                'fas fa-list'
            ));
            $event->addItem($media_manager);

            // Station Manager menu items.
            $station_manager = new MenuItemModel(
                'station_manager_stations',
                'Stations',
                'station_manager_stations',
                [],
                'fas fa-broadcast-tower'
            );
            $event->addItem($station_manager);

            // TVSS menu items.
            $tvss = new MenuItemModel(
                'tvss',
                'TV Schedules',
                'tvss',
                [],
                'fas fa-tv'
            );
            $tvss->addChild(new MenuItemModel(
                'tvss_programs',
                'Programs',
                'tvss_programs',
                [],
                'fas fa-list'
            ));
            $tvss->addChild(new MenuItemModel(
                'tvss_headends',
                'Headends',
                'tvss_headends',
                [],
                'fas fa-list'
            ));
            $tvss->addChild(new MenuItemModel(
                'tvss_listings',
                'Listings',
                'tvss_listings',
                [],
                'fas fa-list'
            ));
            $event->addItem($tvss);

        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $event->addItem(new MenuItemModel(
               'settings',
               'Settings',
               'settings',
               [],
               'fas fa-cogs'
            ));
        }

        if ($this->security->isGranted('ROLE_USER')) {
            $event->addItem(new MenuItemModel(
               'user_logout',
               'Logout',
               'logout',
               [],
               'fas fa-sign-out-alt'
            ));
        }
        else {
            $event->addItem(new MenuItemModel(
               'user_login',
               'Log in',
               'login',
               [],
               'fas fa-sign-in-alt'
            ));
        }

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
