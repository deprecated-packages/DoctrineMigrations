<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\DI;

use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Nette\DI\CompilerExtension;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;
use Zenify\DoctrineMigrations\Configuration\Configuration;


final class MigrationsExtension extends CompilerExtension
{

	/**
	 * @var string[]
	 */
	private $defaults = [
		'table' => 'doctrine_migrations',
		'directory' => '%appDir%/../migrations',
		'namespace' => 'Migrations',
		'codingStandard' => CodeStyle::INDENTATION_TABS
	];


	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$containerBuilder = $this->getContainerBuilder();

		$this->compiler->parseServices(
			$containerBuilder,
			$this->loadFromFile(__DIR__ . '/../config/services.neon')
		);

		$config = $this->getConfig($this->defaults);
		$config = $this->getValidatedConfig($config);

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


	private function addConfigurationDefinition(array $config)
	{
		$containerBuilder = $this->getContainerBuilder();

		$containerBuilder->addDefinition($this->prefix('configuration'))
			->setClass(Configuration::class)
			->addSetup('setMigrationsTableName', [$config['table']])
			->addSetup('setMigrationsDirectory', [$config['directory']])
			->addSetup('setMigrationsNamespace', [$config['namespace']]);
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

	/**
	 * @return array
	 */
	private function getValidatedConfig(array $configuration)
	{
		$this->validateConfig($configuration);
		$configuration = $this->keepBcForDirsOption($configuration);
		$configuration['directory'] = $this->getContainerBuilder()->expand($configuration['directory']);
		return $configuration;
	}


	/**
	 * @deprecated Old `dirs` option to be removed in 3.0, use `directory` instead.
	 *
	 * @return array
	 */
	private function keepBcForDirsOption(array $configuration)
	{
		if (isset($configuration['dirs']) && count($configuration['dirs'])) {
			$configuration['directory'] = reset($configuration['dirs']);
		}
		return $configuration;
	}

}
