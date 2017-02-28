<?php

use CultuurNet\UDB3\IIS\Silex\DatabaseSchemaInstaller;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\SchemaLogConfigurator;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\SchemaRelationConfigurator;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\SchemaXmlConfigurator;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\StoreLoggingDBALRepository;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\StoreRelationDBALRepository;
use CultuurNet\UDB3\IISStore\Stores\Doctrine\StoreXmlDBALRepository;
use CultuurNet\UDB3\IISStore\Stores\StoreRepository;
use DerAlex\Silex\YamlConfigServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Migrations\Configuration\YamlConfiguration;
use Silex\Application;
use ValueObjects\StringLiteral\StringLiteral;

$app = new Application();

$app->register(new YamlConfigServiceProvider(__DIR__ . '/config.yml'));

/**
 * Turn debug on or off.
 */
$app['debug'] = $app['config']['debug'] === true;

$app['dbal_connection'] = $app->share(
    function (Application $app) {
        return DriverManager::getConnection(
            $app['config']['database'],
            null
        );
    }
);

$app['database.migrations.configuration'] = $app->share(
    function (Application $app) {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $app['dbal_connection'];

        $configuration = new YamlConfiguration($connection);
        $configuration->load(__DIR__ . '/migrations.yml');

        return $configuration;
    }
);

$app['database.installer'] = $app->share(
    function (Application $app) {
        $installer = new DatabaseSchemaInstaller(
            $app['dbal_connection'],
            $app['database.migrations.configuration']
        );

        $installer->addSchemaConfigurator(
            new SchemaXmlConfigurator(new StringLiteral('xml'))
        );

        $installer->addSchemaConfigurator(
            new SchemaLogConfigurator(new StringLiteral('log'))
        );

        $installer->addSchemaConfigurator(
            new SchemaRelationConfigurator(new StringLiteral('relation'))
        );

        return $installer;
    }
);

$app['iis.dbal_store.xml'] = $app->share(
    function (Application $app) {
        return new StoreXmlDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('xml')
        );
    }
);

$app['iis.dbal_store.log'] = $app->share(
    function (Application $app) {
        return new StoreLoggingDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('log')
        );
    }
);

$app['iis.dbal_store.relation'] = $app->share(
    function (Application $app) {
        return new StoreRelationDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('relation')
        );
    }
);

$app['iis.dbal_store'] = $app->share(
    function (Application $app) {
        return new StoreRepository(
            $app['iis.dbal_store.log'],
            $app['iis.dbal_store.relation'],
            $app['iis.dbal_store.xml']
        );
    }
);

return $app;
