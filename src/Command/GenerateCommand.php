<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration as BaseConfiguration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand as BaseGenerateCommand;
use Symfony\Component\Console\Input\InputInterface;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class GenerateCommand extends BaseGenerateCommand
{

	/**
	 * @param Configuration|BaseConfiguration $configuration
	 * @param InputInterface $input
	 * @param string $version
	 * @param NULL $up
	 * @param NULL $down
	 * @return string
	 */
	protected function generateMigration(
		BaseConfiguration $configuration, InputInterface $input, $version, $up = NULL, $down = NULL
	) {
		$path = parent::generateMigration($configuration, $input, $version, $up, $down);

		if ($configuration->getCodingStandard() === MigrationsExtension::CODING_STANDARD_TABS) {
			$code = file_get_contents($path);
			$code = preg_replace("/ {4}/", "\t", $code);
			file_put_contents($path, $code);
		}

		return $path;
	}

}
