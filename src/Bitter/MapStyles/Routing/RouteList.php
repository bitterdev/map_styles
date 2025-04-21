<?php

namespace Bitter\MapStyles\Routing;

use Bitter\MapStyles\API\V1\Middleware\FractalNegotiatorMiddleware;
use Bitter\MapStyles\API\V1\Configurator;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\MapStyles\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/map_styles')
            ->routes('dialogs/support.php', 'map_styles');
    }
}