<?php

namespace CultuurNet\UDB3\IIS\Silex\Console;

use CultuurNet\UDB3\IIS\Silex\DatabaseSchemaInstaller;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Install the application (db schema insertion, etc.)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getDatabaseSchemaInstaller()->installSchema();

        $output->writeln('Database schema installed.');
    }

    /**
     * @return DatabaseSchemaInstaller
     */
    protected function getDatabaseSchemaInstaller()
    {
        $app = $this->getSilexApplication();
        return $app['database.installer'];
    }
}
