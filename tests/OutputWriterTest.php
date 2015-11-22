<?php

namespace Zenify\DoctrineMigrations\Tests;

use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;
use Zenify\DoctrineMigrations\OutputWriter;


class OutputWriterTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var OutputWriter
	 */
	private $outputWriter;


	protected function setUp()
	{
		$this->outputWriter = new OutputWriter;
	}


	public function testGetOutputWriterWhenNeeded()
	{
		$consoleOutput = PHPUnit_Framework_Assert::getObjectAttribute($this->outputWriter, 'consoleOutput');
		$this->assertNull($consoleOutput);

		$this->outputWriter->write('');

		$consoleOutput = PHPUnit_Framework_Assert::getObjectAttribute($this->outputWriter, 'consoleOutput');
		$this->assertInstanceOf(ConsoleOutput::class, $consoleOutput);
	}

}
