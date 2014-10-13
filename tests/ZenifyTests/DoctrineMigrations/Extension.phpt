<?php

namespace ZenifyTests\DoctrineMigrations;

use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;


$container = require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends TestCase
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
		Assert::type(
			'Zenify\DoctrineMigrations\Configuration\Configuration',
			$this->container->getByType('Zenify\DoctrineMigrations\Configuration\Configuration')
		);

		Assert::type(
			'Zenify\DoctrineMigrations\OutputWriter',
			$this->container->getByType('Zenify\DoctrineMigrations\OutputWriter')
		);
	}

}


\run(new ExtensionTest($container));
