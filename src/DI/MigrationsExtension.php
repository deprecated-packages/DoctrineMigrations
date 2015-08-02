<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Assert\Assertion;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Nette\DI\CompilerExtension;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\Configuration\Configuration;


class MigrationsExtension extends CompilerExtension
{

	/**
	 * @var string
	 */
	const CODING_STANDARD_TABS = 'tabs';

	/**
	 * @var string
	 */
	const CODING_STANDARD_SPACES = 'spaces';

	/**
	 * @var mixed[]
	 */
	private $defaults = [
		'table' => 'doctrine_migrations',
		'dirs' => [],
		'namespace' => 'Migrations',
		'codingStandard' => self::CODING_STANDARD_TABS
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$this->validateConfigTypes($config);

		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/../config/services/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);

		if (count($config['dirs']) === 0) {
			$config['dirs'] = [$containerBuilder->expand('%appDir%/../migrations')];
		}

		$configurationDefinition = $containerBuilder->addDefinition($this->prefix('configuration'))
			->setClass(Configuration::class)
			->addSetup('setMigrationsTableName', [$config['table']])
			->addSetup('setMigrationsDirectory', [reset($config['dirs'])])
			->addSetup('setMigrationsNamespace', [$config['namespace']])
			->addSetup('setCodingStandard', [$config['codingStandard']]);

		$dirs = array_unique($config['dirs']);
		foreach ($dirs as $dir) {
			$configurationDefinition->addSetup('registerMigrationsFromDirectory', [$dir]);
		}
	}


	private function validateConfigTypes(array $config)
	{
		Assertion::string($config['table']);
		Assertion::isArray($config['dirs']);
		Assertion::string($config['namespace']);
		Assertion::string($config['codingStandard']);
	}


	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		$containerBuilder = $this->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$this->setConfigurationToCommands();
		$this->loadCommandsToApplication();
	}


	private function setConfigurationToCommands()
	{
		$containerBuilder = $this->getContainerBuilder();
		$configurationDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(Configuration::class));
		foreach ($containerBuilder->findByType(AbstractCommand::class) as $commandDefinition) {
			$commandDefinition->addSetup('setMigrationConfiguration', ['@' . $configurationDefinition->getClass()]);
		}
	}


	private function loadCommandsToApplication()
	{
		$containerBuilder = $this->getContainerBuilder();
		$applicationDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(Application::class));
		foreach ($containerBuilder->findByType(AbstractCommand::class) as $commandDefinition) {
			$applicationDefinition->addSetup('add', ['@' . $commandDefinition->getClass()]);
		}
	}

}
