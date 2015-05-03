<?php

namespace ZenifyTests\DoctrineMigrations\DI;

use Nette;
use PHPUnit_Framework_TestCase;
use Zenify;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\OutputWriter;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


class MigrationsExtensionTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	public function testExtension()
	{
		$this->assertInstanceOf(Configuration::class, $this->container->getByType(Configuration::class));
		$this->assertInstanceOf(OutputWriter::class,$this->container->getByType(OutputWriter::class));
	}

}
