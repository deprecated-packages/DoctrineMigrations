<?php

namespace Zenify\DoctrineMigrations\Tests;

use Nette\Configurator;
use Nette\DI\Container;


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
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->enableDebugger(TEMP_DIR . '/log');
		$configurator->addConfig($config);
		return $configurator->createContainer();
	}

}
