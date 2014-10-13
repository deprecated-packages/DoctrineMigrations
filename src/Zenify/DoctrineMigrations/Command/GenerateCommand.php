<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Command;

use Doctrine;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Zenify;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class GenerateCommand extends Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand
{

	/**
	 * @param Configuration $conf
	 * @param InputInterface $input
	 * @param string $version
	 * @param NULL $up
	 * @param NULL $down
	 * @return string
	 */
	protected function generateMigration(Configuration $conf, InputInterface $input, $version, $up = NULL, $down = NULL)
	{
		$path = parent::generateMigration($conf, $input, $version, $up, $down);

		/** @var Zenify\DoctrineMigrations\Configuration\Configuration $conf */
		if ($conf->getCs() === MigrationsExtension::CS_TABS) {
			$code = file_get_contents($path);
			$code = preg_replace("/ {4}/", "\t", $code);
			file_put_contents($path, $code);
		}

		return $path;
	}

}
