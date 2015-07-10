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
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class Configuration extends BaseConfiguration
{

	/**
	 * @var string
	 */
	private $codingStandard = MigrationsExtension::CODING_STANDARD_TABS;


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
	 * @param string $codingStandard
	 */
	public function setCodingStandard($codingStandard)
	{
		$this->codingStandard = $codingStandard;
	}


	/**
	 * @return string
	 */
	public function getCodingStandard()
	{
		return $this->codingStandard;
	}

}
