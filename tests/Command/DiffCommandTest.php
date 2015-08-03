<?php

namespace Zenify\DoctrineMigrations\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Zenify\DoctrineMigrations\Command\DiffCommand;


class DiffCommandTest extends AbstractCommandTest
{

	/**
	 * @var DiffCommand
	 */
	private $command;


	protected function setUp()
	{
		$this->command = $this->getCommand('migrations:diff');
	}


	public function testRun()
	{
		$commandTester = new CommandTester($this->command);
		$commandTester->execute(['command' => $this->command->getName()]);

		$this->assertContains('No changes detected in your mapping information.', $commandTester->getDisplay());
	}

}
