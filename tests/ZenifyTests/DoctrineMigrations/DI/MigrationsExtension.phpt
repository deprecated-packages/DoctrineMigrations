<?php

namespace ZenifyTests\DoctrineMigrations\DI;

use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\OutputWriter;


$container = require_once __DIR__ . '/../../bootstrap.php';


class MigrationsExtensionTest extends TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testExtension()
	{
		Assert::type(Configuration::class, $this->container->getByType(Configuration::class));
		Assert::type(OutputWriter::class, $this->container->getByType(OutputWriter::class));
	}

}


(new MigrationsExtensionTest($container))->run();
