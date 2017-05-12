<?php

namespace CultuurNet\UDB3\IIS\Silex\Controller;

use CultuurNet\UDB3\IIS\Silex\Relation\RelationController;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class RelationControllerProvider implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        $app['iis.relation_controller'] = $app->share(
            function (Application $app) {
                return new RelationController($app['iis.dbal_store']);
            }
        );

        /* @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/{externalId}',
            'iis.relation_controller:get'
        );

        return $controllers;
    }
}
