<?php

namespace CultuurNet\UDB3\IIS\Silex\Controller;

use CultuurNet\UDB3\IIS\Silex\Event\EventController;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class EventControllerProvider implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $app['iis.event_controller'] = $app->share(
            function (Application $app) {
                return new EventController();
            }
        );

        /* @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/{eventCdbid}',
            'iis.event_controller:get'
        );

        return $controllers;
    }
}
