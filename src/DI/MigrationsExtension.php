<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Assert\Assertion;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Nette\DI\CompilerExtension;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;


final class MigrationsExtension extends CompilerExtension
{

	/**
	 * @var mixed[]
	 */
	private $defaults = [
		'table' => 'doctrine_migrations',
		'dirs' => [],
		'namespace' => 'Migrations',
		'codingStandard' => CodeStyle::INDENTATION_TABS
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();
		$services = $this->loadFromFile(__DIR__ . '/../config/services/services.neon');
		$this->compiler->parseServices($containerBuilder, $services);

		$config = $this->getValidatedConfig($this->defaults);

		$containerBuilder->addDefinition($this->prefix('codeStyle'))
			->setClass(CodeStyle::class)
			->setArguments([$config['codingStandard']]);

		$this->addConfigurationDefinition($config);
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


	/**
	 * @return array
	 */
	private function getValidatedConfig(array $defaults)
	{
		$config = parent::getConfig($defaults);
		if (count($config['dirs']) === 0) {
			$containerBuilder = $this->getContainerBuilder();
			$config['dirs'] = [$containerBuilder->expand('%appDir%/../migrations')];
		}

		$this->validateConfigTypes($config);

		return $config;
	}


	private function validateConfigTypes(array $config)
	{
		Assertion::string($config['table']);
		Assertion::isArray($config['dirs']);
		Assertion::string($config['namespace']);
		Assertion::string($config['codingStandard']);
	}


	private function addConfigurationDefinition(array $config)
	{
		$containerBuilder = $this->getContainerBuilder();

		$configurationDefinition = $containerBuilder->addDefinition($this->prefix('configuration'))
			->setClass(Configuration::class)
			->addSetup('setMigrationsTableName', [$config['table']])
			->addSetup('setMigrationsDirectory', [reset($config['dirs'])])
			->addSetup('setMigrationsNamespace', [$config['namespace']]);

		$dirs = array_unique($config['dirs']);
		foreach ($dirs as $dir) {
			$configurationDefinition->addSetup('registerMigrationsFromDirectory', [$dir]);
		}
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
		foreach ($containerBuilder->findByType(AbstractCommand::class) as $name => $commandDefinition) {
			$applicationDefinition->addSetup('add', ['@' . $name]);
		}
	}

}
