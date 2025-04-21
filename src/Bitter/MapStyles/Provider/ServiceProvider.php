<?php

namespace Bitter\MapStyles\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Asset\AssetInterface;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Page\Page;
use Concrete\Core\Routing\RouterInterface;
use Bitter\MapStyles\Routing\RouteList;
use Concrete\Core\Site\Config\Liaison;
use Concrete\Core\Site\Service;
use Concrete\Core\View\View;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ServiceProvider extends Provider
{
    protected RouterInterface $router;
    protected EventDispatcherInterface $eventDispatcher;
    protected Liaison $siteConfig;
    protected Repository $config;

    public function __construct(
        Application              $app,
        RouterInterface          $router,
        EventDispatcherInterface $eventDispatcher,
        Service                  $siteService,
        Repository               $config
    )
    {
        parent::__construct($app);

        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->siteConfig = $siteService->getActiveSiteForEditing()->getConfigRepository();
        $this->config = $config;
    }

    public function register()
    {
        $this->registerRoutes();
        $this->registerAssets();
        $this->registerEventHandlers();
    }

    /** @noinspection PhpArgumentWithoutNamedIdentifierInspection */
    private function registerEventHandlers()
    {
        $this->eventDispatcher->addListener('on_page_output', function ($event) {
            /** @var $event GenericEvent */
            $htmlCode = $event->getArgument('contents');

            $searchFor = sprintf(
                '<script defer src="https://maps.googleapis.com/maps/api/js?callback=concreteGoogleMapInit&key=%s"></script>',
                $this->config->get('app.api_keys.google.maps')
            );

            $replaceWith = sprintf(
                '<script defer src="https://maps.googleapis.com/maps/api/js?callback=mapStylerGoogleMapInit&key=%s&language=%s"></script>',
                $this->config->get('app.api_keys.google.maps'),
                Localization::activeLanguage()
            );

            $htmlCode = str_replace($searchFor, $replaceWith, $htmlCode);

            $event->setArgument("contents", $htmlCode);
        });

        $this->eventDispatcher->addListener("on_before_render", function () {
            $c = Page::getCurrentPage();

            if ($c instanceof Page && !$c->isError() && !$c->isSystemPage()) {
                $v = View::getInstance();

                $v->requireAsset("javascript", "map-styles");
                /** @noinspection JSUnresolvedFunction */
                /** @noinspection JSUnresolvedVariable */
                $v->addFooterItem(sprintf(
                    "<script>CCM_MAP_STYLES = %s;</script>",
                    json_encode($this->siteConfig->get("map_styles.styles", []))
                ));
            }
        });
    }

    private function registerAssets()
    {
        $al = AssetList::getInstance();
        $al->register("javascript", "map-styles", "js/map-styles.js", ["position" => AssetInterface::ASSET_POSITION_HEADER], "map_styles");
    }

    private function registerRoutes()
    {
        $this->router->loadRouteList(new RouteList());
    }
}