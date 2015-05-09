<?php

namespace ZenifyTests\DoctrineMigrations\DI;

use Nette;
use PHPUnit_Framework_TestCase;
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
		$this->assertInstanceOf(
			'Zenify\DoctrineMigrations\Configuration\Configuration',
			$this->container->getByType('Zenify\DoctrineMigrations\Configuration\Configuration')
		);

		$this->assertInstanceOf(
			'Zenify\DoctrineMigrations\OutputWriter',
			$this->container->getByType('Zenify\DoctrineMigrations\OutputWriter')
		);
	}

}
