<?php

namespace Zenify\DoctrineMigrations\Tests\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


abstract class AbstractCommandTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @param string $name
	 * @return AbstractCommand
	 */
	protected function getCommand($name)
	{
		$container = (new ContainerFactory)->create();

		/** @var Configuration $configuration */
		$configuration = $container->getByType(Configuration::class);
		@mkdir($this->getMigrationsDir());
		$configuration->setMigrationsDirectory($this->getMigrationsDir());

		/** @var Application $application */
		$application = $container->getByType(Application::class);
		return $application->find($name);
	}


	/**
	 * @return string
	 */
	protected function getMigrationsDir()
	{
		return TEMP_DIR . '/Migrations';
	}

}
