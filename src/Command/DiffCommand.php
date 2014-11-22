<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Command;

use Doctrine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class DiffCommand extends Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand
{

	public function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		/** @var Configuration $configuration */
		$configuration = $this->getMigrationConfiguration($input, $output);
		if ($configuration->getCs() === MigrationsExtension::CS_TABS) {
			$version = date('YmdHis');
			$configuration = $this->getMigrationConfiguration($input, $output);
			$dir = $configuration->getMigrationsDirectory();
			$dir = $dir ? $dir : getcwd();
			$dir = rtrim($dir, '/');

			// get last (current) created version
			$fileName = function ($version) use ($dir) {
				return $dir . '/Version' . $version . '.php';
			};
			while ( ! file_exists($fileName($version))) {
				$version--;
			}

			$path = $fileName($version);
			$code = file_get_contents($path);
			$code = preg_replace("/ {4}/", "\t", $code);
			file_put_contents($path, $code);
		}
	}

}
