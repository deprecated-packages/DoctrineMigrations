<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Configuration;

use Doctrine;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\MigrationException;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Migrations\Version;
use Nette\DI\Container;


class Configuration extends Doctrine\DBAL\Migrations\Configuration\Configuration
{
	/** @var Container */
	private $container;


	public function __construct(Connection $connection, OutputWriter $outputWriter, Container $container)
	{
		parent::__construct($connection, $outputWriter);
		$this->container = $container;
	}


	/**
	 * @param string $direction
	 * @param string $to
	 * @return Version[]
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
	 * @param string $version
	 * @return Version
	 * @throws MigrationException
	 */
	public function getVersion($version)
	{
		$version = parent::getVersion($version);
		$this->container->callInjects($version->getMigration());
		return $version;
	}

}
