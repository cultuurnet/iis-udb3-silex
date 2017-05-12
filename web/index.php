<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CultuurNet\UDB3\IIS\Silex\Controller\EventControllerProvider;
use CultuurNet\UDB3\IIS\Silex\Controller\RelationControllerProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

/** @var Application $app */
$app = require __DIR__ . '/../bootstrap.php';

/**
 * Allow to use services as controllers.
 */
$app->register(new ServiceControllerServiceProvider());

$app->mount('/events', new EventControllerProvider());

$app->mount('/relations', new RelationControllerProvider());

$app->run();
