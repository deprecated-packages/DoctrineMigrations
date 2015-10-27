<?php

namespace Zenify\DoctrineMigrations\Tests\EventSubscriber;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


final class RegisterMigrationsEventSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var Configuration
	 */
	private $configuration;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->application = $container->getByType(Application::class);

		$this->configuration = $container->getByType(Configuration::class);
		$this->configuration->setMigrationsDirectory($this->getMigrationsDirectory());
	}


	public function testDispatching()
	{
		$this->assertSame(0, $this->configuration->getNumberOfAvailableMigrations());

		$input = new ArrayInput(['command' => 'migrations:status']);
		$output = new BufferedOutput;

		$result = $this->application->run($input, $output);
		$this->assertSame(0, $result);

		$this->assertSame(2, $this->configuration->getNumberOfAvailableMigrations());
	}


	/**
	 * @return string
	 */
	private function getMigrationsDirectory()
	{
		return __DIR__ . '/../Migrations';
	}

}
