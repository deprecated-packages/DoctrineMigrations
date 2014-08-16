<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Kdyby\Console\DI\ConsoleExtension;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;


class MigrationsExtension extends CompilerExtension
{
	/** @var array */
	protected $defaults = [
		'table' => 'doctrine_migrations',
		'dirs' => ['%appDir%/../migrations'],
		'namespace' => 'Migrations',
		'enabled' => FALSE
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

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('consoleOutput'))
			->setClass('Zenify\DoctrineMigrations\OutputWriter');

		Validators::assertField($config, 'dirs', 'list');
		$configuration = $builder->addDefinition($this->prefix('configuration'))
			->setClass('Zenify\DoctrineMigrations\Configuration\Configuration')
			->addSetup('setMigrationsTableName', [$config['table']])
			->addSetup('setMigrationsDirectory', [reset($config['dirs'])])
			->addSetup('setMigrationsNamespace', [$config['namespace']]);

		$dirs = array_unique($config['dirs']);
		foreach ($dirs as $dir) {
			$configuration->addSetup('registerMigrationsFromDirectory', [$dir]);
		}

		foreach ($this->loadFromFile(__DIR__ . '/commands.neon') as $i => $class) {
			$builder->addDefinition($this->prefix('command.' . $i))
				->setClass($class)
				->addTag(ConsoleExtension::COMMAND_TAG)
				->addSetup('setMigrationConfiguration', [$configuration]);
		}
	}

}
