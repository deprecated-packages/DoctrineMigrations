<?php

/**
 * Test: Zenify\DoctrineMigrations\Extension.
 *
 * @testCase
 */

namespace ZenifyTests\DoctrineMigrations;

use Nette;
use Tester\Assert;
use Tester\TestCase;
use Zenify;


$container = require_once __DIR__ . '/../bootstrap.php';


class ExtensionTest extends TestCase
{
	/** @var Nette\DI\Container */
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function testExtension()
	{
		$configuration = $this->container->getByType('Zenify\DoctrineMigrations\Configuration\Configuration');
		Assert::type('Zenify\DoctrineMigrations\Configuration\Configuration', $configuration);

		$outputWriter = $this->container->getByType('Zenify\DoctrineMigrations\OutputWriter');
		Assert::type('Zenify\DoctrineMigrations\OutputWriter', $outputWriter);
	}

}


\run(new ExtensionTest($container));
