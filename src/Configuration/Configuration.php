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
	protected $cs;

	/**
	 * @var array
	 */
	private $dirs = [];

	/**
	 * @var bool
	 */
	private $isInitialized = FALSE;


	public function __construct(Connection $connection, OutputWriter $outputWriter, Container $container)
	{
		parent::__construct($connection, $outputWriter);
		$this->container = $container;
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


	public function addMigrationsDirectory($dir)
	{
		if ($this->getMigrationsDirectory() === NULL) {
			$this->setMigrationsDirectory($dir);
		}

		$this->dirs[] = $dir;
	}


	protected function initializeMigrations()
	{
		if ($this->isInitialized === TRUE) {
			return;
		}

		foreach ($this->dirs as $dir) {
			$this->registerMigrationsFromDirectory($dir);
		}

		$this->isInitialized = TRUE;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getMigrationsToExecute($direction, $to)
	{
		$this->initializeMigrations();
		$versions = parent::getMigrationsToExecute($direction, $to);
		foreach ($versions as $version) {
			$this->container->callInjects($version->getMigration());
		}
		return $versions;
	}



	public function registerMigration($version, $class)
	{
		if (!class_exists($class)) {
			throw new \RuntimeException(sprintf('Class %s not found.', $class));
		}

		return parent::registerMigration($version, $class);
	}



	/**
	 * {@inheritDoc}
	 */
	public function getVersion($version)
	{
		$this->initializeMigrations();
		$version = parent::getVersion($version);
		$this->container->callInjects($version->getMigration());
		return $version;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getMigrations()
	{
		$this->initializeMigrations();
		return parent::getMigrations();
	}


	/**
	 * {@inheritDoc}
	 */
	public function hasVersion($version)
	{
		$this->initializeMigrations();
		return parent::hasVersion($version);
	}


	/**
	 * {@inheritDoc}
	 */
	public function getAvailableVersions()
	{
		$this->initializeMigrations();
		return parent::getAvailableVersions();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getCurrentVersion()
	{
		$this->initializeMigrations();
		return parent::getCurrentVersion();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getPrevVersion()
	{
		$this->initializeMigrations();
		return parent::getPrevVersion();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getNextVersion()
	{
		$this->initializeMigrations();
		return parent::getNextVersion();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getRelativeVersion($version, $delta)
	{
		$this->initializeMigrations();
		return parent::getRelativeVersion($version, $delta);
	}


	/**
	 * {@inheritDoc}
	 */
	public function resolveVersionAlias($alias)
	{
		$this->initializeMigrations();
		return parent::resolveVersionAlias($alias);
	}


	/**
	 * {@inheritDoc}
	 */
	public function getNumberOfAvailableMigrations()
	{
		$this->initializeMigrations();
		return parent::getNumberOfAvailableMigrations();
	}


	/**
	 * {@inheritDoc}
	 */
	public function getLatestVersion()
	{
		$this->initializeMigrations();
		return parent::getLatestVersion();
	}

}
