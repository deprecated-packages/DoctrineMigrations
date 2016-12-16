<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Configuration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration as BaseConfiguration;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Migrations\Version;
use Nette\DI\Container;


final class Configuration extends BaseConfiguration
{

	public function __construct(Container $container, Connection $connection, OutputWriter $outputWriter = NULL)
	{
		$this->container = $container;
		parent::__construct($connection, $outputWriter);
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
	 * @return Version|string
	 */
	public function getVersion($version)
	{
		$version = parent::getVersion($version);
		$this->container->callInjects($version->getMigration());
		return $version;
	}

}
