<?php

namespace Zenify\DoctrineMigrations\Tests\EventSubscriber;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


class ChangeCodingStandardEventSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Application
	 */
	private $application;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->application = $container->getByType(Application::class);
		$this->application->setAutoExit(FALSE);

		/** @var Configuration $configuration */
		$configuration = $container->getByType(Configuration::class);
		$configuration->setMigrationsDirectory($this->getMigrationsDirectory());
		@mkdir($this->getMigrationsDirectory());
	}


	public function testDispatching()
	{
		$input = new ArrayInput(['command' => 'migrations:generate']);
		$output = new BufferedOutput;

		$result = $this->application->run($input, $output);
		$this->assertSame(0, $result);
		$this->assertContains('Generated new migration class to', $output->fetch());
	}


	/**
	 * @return string
	 */
	private function getMigrationsDirectory()
	{
		return TEMP_DIR . '/Migrations';
	}

}
