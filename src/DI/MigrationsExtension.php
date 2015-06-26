<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Kdyby\Console\DI\ConsoleExtension;
use Nette\DI\CompilerExtension;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\OutputWriter;


class MigrationsExtension extends CompilerExtension
{

	const CS_TABS = 'tabs';

	/**
	 * @var array
	 */
	private $defaults = [
		'table' => 'doctrine_migrations',
		'dirs' => [],
		'namespace' => 'Migrations',
		'enabled' => FALSE,
		'codingStandard' => self::CS_TABS # or "spaces"
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

		$builder = $this->getContainerBuilder();

		if (count($config['dirs']) === 0) {
			$config['dirs'] = [$builder->expand('%appDir%/../migrations')];
		}

		$builder->addDefinition($this->prefix('consoleOutput'))
			->setClass(OutputWriter::class);

		$configuration = $builder->addDefinition($this->prefix('configuration'))
			->setClass(Configuration::class)
			->addSetup('setMigrationsTableName', [$config['table']])
			->addSetup('setMigrationsDirectory', [reset($config['dirs'])])
			->addSetup('setMigrationsNamespace', [$config['namespace']])
			->addSetup('setCs', [$config['codingStandard']]);

		$dirs = array_unique($config['dirs']);
		foreach ($dirs as $dir) {
			$configuration->addSetup('addMigrationsDirectory', [$dir]);
		}

		foreach ($this->loadFromFile(__DIR__ . '/commands.neon') as $i => $class) {
			$builder->addDefinition($this->prefix('command.' . $i))
				->setClass($class)
				->addTag(ConsoleExtension::COMMAND_TAG)
				->addSetup('setMigrationConfiguration', [$configuration]);
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
