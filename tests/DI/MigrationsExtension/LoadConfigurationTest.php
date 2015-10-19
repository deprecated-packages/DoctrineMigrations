<?php

namespace Zenify\DoctrineMigrations\Tests\DI\MigrationsExtension;

use Assert\InvalidArgumentException;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class LoadConfigurationTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var MigrationsExtension
	 */
	private $extension;


	protected function setUp()
	{
		$this->extension = new MigrationsExtension;
		$containerBuilder = new ContainerBuilder;
		$containerBuilder->parameters = ['appDir' => __DIR__];
		$this->extension->setCompiler(new Compiler($containerBuilder), 'migrations');
	}


	public function testLoadConfiguration()
	{
		$this->extension->loadConfiguration();
		$containerBuilder = $this->extension->getContainerBuilder();
		$containerBuilder->prepareClassList();

		$configurationDefinition = $containerBuilder->getDefinition($containerBuilder->getByType(Configuration::class));
		$this->assertSame(Configuration::class, $configurationDefinition->getClass());
	}

}
