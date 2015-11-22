<?php

namespace Zenify\DoctrineMigrations\Tests\EventSubscriber;

use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Zenify\DoctrineMigrations\OutputWriter;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


final class SetConsoleOutputEventSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var OutputWriter
	 */
	private $outputWriter;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->application = $container->getByType(Application::class);
		$this->outputWriter = $container->getByType(OutputWriter::class);
	}


	public function testDispatching()
	{
		$this->assertNull(
			PHPUnit_Framework_Assert::getObjectAttribute($this->outputWriter, 'consoleOutput')
		);

		$input = new ArrayInput(['command' => 'migrations:status']);
		$output = new BufferedOutput;
		$this->application->run($input, $output);

		$this->assertInstanceOf(
			OutputInterface::class,
			PHPUnit_Framework_Assert::getObjectAttribute($this->outputWriter, 'consoleOutput')
		);
	}

}
