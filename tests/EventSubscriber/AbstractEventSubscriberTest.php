<?php

namespace Zenify\DoctrineMigrations\Tests\EventSubscriber;

use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


abstract class AbstractEventSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var Application
	 */
	protected $application;


	/**
	 * @param string $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = NULL, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		$this->data = $data;
	}


	/**
	 * Data provider for all tests.
	 *
	 * @return string[]
	 */
	public function getConfigFiles()
	{
		return [
			[__DIR__ . '/../config/default.neon'],
			[__DIR__ . '/../config/symnedi.neon'],
		];
	}


	protected function setUp()
	{
		$container = (new ContainerFactory)->createWithConfig($this->data);
		$this->container = $container;
		$this->application = $container->getByType(Application::class);
	}

}
