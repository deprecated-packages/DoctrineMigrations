<?php

namespace Zenify\DoctrineMigrations\Tests\DI\MigrationsExtension;

use Assert\InvalidArgumentException;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use PHPUnit_Framework_TestCase;
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


	public function testTableAssertion()
	{
		$this->setExpectedException(InvalidArgumentException::class);
		$this->extension->setConfig(['table' => 123]);
		$this->extension->loadConfiguration();
	}


	public function testNamespaceAssertion()
	{
		$this->setExpectedException(InvalidArgumentException::class);
		$this->extension->setConfig(['namespace' => 123]);
		$this->extension->loadConfiguration();
	}


	public function testDirsAssertion()
	{
		$this->setExpectedException(InvalidArgumentException::class);
		$this->extension->setConfig(['dirs' => 123]);
		$this->extension->loadConfiguration();
	}


	public function testCodingStandardAssertion()
	{
		$this->setExpectedException(InvalidArgumentException::class);
		$this->extension->setConfig(['codingStandard' => 123]);
		$this->extension->loadConfiguration();
	}

}
