<?php

namespace CultuurNet\UDB3\IIS\Silex\Controller;

use CultuurNet\UDB3\IIS\Silex\Log\LogController;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class LogControllerProvider implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $app['iis.log_controller'] = $app->share(
            function (Application $app) {
                return new LogController($app['config']['logging_folder']);
            }
        );

        /* @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/{date}',
            'iis.log_controller:get'
        );

        return $controllers;
    }
}
