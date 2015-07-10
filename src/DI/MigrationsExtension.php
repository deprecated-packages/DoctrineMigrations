<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
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
	 * @var array
	 */
	private $defaults = [
		'table' => 'doctrine_migrations',
		'dirs' => [],
		'namespace' => 'Migrations',
		'enabled' => FALSE,
		'codingStandard' => self::CODING_STANDARD_TABS
	];


	public function __construct()
	{
		$this->defaults['enabled'] = PHP_SAPI === 'cli';
	}


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		if ($config['enabled'] === FALSE) {
			return;
		}
		$this->validateConfigTypes($config);

		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/services.neon');
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

		foreach ($this->loadFromFile(__DIR__ . '/commands.neon') as $i => $class) {
			$containerBuilder->addDefinition($this->prefix('command.' . $i))
				->setClass($class)
				->addTag('kdyby.console.command')
				->addSetup('setMigrationConfiguration', [$configurationDefinition]);
		}
	}


	/**
	 * @throws AssertionException
	 */
	private function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'table', 'string');
		Validators::assertField($config, 'dirs', 'list');
		Validators::assertField($config, 'namespace', 'string');
		Validators::assertField($config, 'codingStandard', 'string');
	}

}
