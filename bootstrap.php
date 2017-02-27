<?php

use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Application;

$app = new Application();

$app->register(new YamlConfigServiceProvider(__DIR__ . '/config.yml'));

/**
 * Turn debug on or off.
 */
$app['debug'] = $app['config']['debug'] === true;

return $app;
