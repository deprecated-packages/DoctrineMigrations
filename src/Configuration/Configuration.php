<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Configuration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration as BaseConfiguration;
use Doctrine\DBAL\Migrations\OutputWriter;
use Nette\DI\Container;


final class Configuration extends BaseConfiguration
{

	public function __construct(Connection $connection, OutputWriter $outputWriter, Container $container)
	{
		parent::__construct($connection, $outputWriter);
		$this->container = $container;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getMigrationsToExecute($direction, $to)
	{
		$versions = parent::getMigrationsToExecute($direction, $to);
		foreach ($versions as $version) {
			$this->container->callInjects($version->getMigration());
		}
		return $versions;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getVersion($version)
	{
		$version = parent::getVersion($version);
		$this->container->callInjects($version->getMigration());
		return $version;
	}


	/**
	 * @param string $directory
	 */
	public function setMigrationsDirectory($directory)
	{
		$this->createDirectoryIfNotExists($directory);
		parent::setMigrationsDirectory($directory);
	}


	/**
	 * @param string $directory
	 */
	private function createDirectoryIfNotExists($directory)
	{
		if ( ! file_exists($directory)) {
			mkdir($directory, 0755, TRUE);
		}
	}

}
