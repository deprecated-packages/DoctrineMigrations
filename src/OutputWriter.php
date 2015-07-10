<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations;

use Doctrine;
use Doctrine\DBAL\Migrations\OutputWriter as DoctrineOutputWriter;
use Symfony\Component\Console\Output\ConsoleOutput;


class OutputWriter extends DoctrineOutputWriter
{

	/**
	 * @var ConsoleOutput
	 */
	private $consoleOutput;


	/**
	 * @param string $message
	 */
	public function write($message)
	{
		$this->getConsoleOutput()->writeln($message);
	}


	/**
	 * @return ConsoleOutput
	 */
	private function getConsoleOutput()
	{
		if ($this->consoleOutput === NULL) {
			$this->consoleOutput = new ConsoleOutput;
		}
		return $this->consoleOutput;
	}

}
