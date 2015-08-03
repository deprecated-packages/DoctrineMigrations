<?php

namespace Zenify\DoctrineMigrations\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Zenify\DoctrineMigrations\Command\GenerateCommand;


class GenerateCommandTest extends AbstractCommandTest
{

	/**
	 * @var GenerateCommand
	 */
	private $command;


	protected function setUp()
	{
		$this->command = $this->getCommand('migrations:generate');
	}


	public function testRun()
	{
		$commandTester = new CommandTester($this->command);
		$commandTester->execute(['command' => $this->command->getName()]);

		$this->assertContains('Generated new migration class to', $commandTester->getDisplay());
		$this->assertContains($this->getMigrationsDir(), $commandTester->getDisplay());
	}

}
