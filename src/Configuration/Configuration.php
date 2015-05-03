<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Configuration;

use Doctrine;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\OutputWriter;
use Nette\DI\Container;


class Configuration extends Doctrine\DBAL\Migrations\Configuration\Configuration
{

	/**
	 * @var string
	 */
	private $cs;


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
	 * @param string $cs
	 */
	public function setCs($cs)
	{
		$this->cs = $cs;
	}


	/**
	 * @return string
	 */
	public function getCs()
	{
		return $this->cs;
	}

}
