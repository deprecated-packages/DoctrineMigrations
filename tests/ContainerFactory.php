<?php

namespace Zenify\DoctrineMigrations\Tests;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\Utils\FileSystem;


final class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		return $this->createWithConfig(__DIR__ . '/config/default.neon');
	}


	/**
	 * @return Container
	 */
	public function createWithConfig($config)
	{
		$configurator = new Configurator;
		$configurator->setTempDirectory($this->createAndReturnTempDir());
		$configurator->addConfig($config);
		return $configurator->createContainer();
	}


	private function createAndReturnTempDir()
	{
		$tempDir = sys_get_temp_dir() . '/doctrine-migrations';
		FileSystem::delete($tempDir);
		FileSystem::createDir($tempDir);
		return $tempDir;
	}

}
