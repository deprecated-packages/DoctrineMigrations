<?php

namespace ZenifyTests\DoctrineMigrations\DI;

use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\OutputWriter;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


class MigrationsExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Container
	 */
	private $container;


	protected function setUp()
	{
		$this->container = (new ContainerFactory)->create();
		@mkdir($this->getMigrationsDir());
	}


	public function testExtension()
	{
		$this->assertInstanceOf(Configuration::class, $this->container->getByType(Configuration::class));
		$this->assertInstanceOf(OutputWriter::class, $this->container->getByType(OutputWriter::class));
	}


	protected function tearDown()
	{
		rmdir($this->getMigrationsDir());
	}


	/**
	 * @return string
	 */
	private function getMigrationsDir()
	{
		return TEMP_DIR . '/../../../migrations';
	}

}
