<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration as DoctrineConfiguration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand as DoctrineDiffCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class DiffCommand extends DoctrineDiffCommand
{

	/**
	 * @var string
	 */
	private $migrationsDirectory;


	public function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		/** @var Configuration $configuration */
		$configuration = $this->getMigrationConfiguration($input, $output);
		$this->migrationsDirectory = $this->getMigrationsDirectory($configuration);

		if ($configuration->getCodingStandard() === MigrationsExtension::CODING_STANDARD_TABS) {
			$version = $this->getCurrentVersionName();

			$i = 0;
			while ( ! file_exists($this->getMigrationFileByVersion($version)) && $i <= 10) {
				$version--;
				$i++;
			}

			$path = $this->getMigrationFileByVersion($version);
			if ( ! file_exists($path)) {
				return;
			}

			CodeStyle::convertSpacesToTabsForFile($path);
		}
	}


	/**
	 * @return string
	 */
	private function getCurrentVersionName()
	{
		return date('YmdHis');
	}


	/**
	 * @param string $version
	 * @return string
	 */
	private function getMigrationFileByVersion($version)
	{
		return $this->migrationsDirectory . '/Version' . $version . '.php';
	}


	/**
	 * @return string
	 */
	private function getMigrationsDirectory(DoctrineConfiguration $configuration)
	{
		$dir = $configuration->getMigrationsDirectory();
		$dir = $dir ? $dir : getcwd();
		return rtrim($dir, '/');
	}

}
