<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\EventSubscriber;

use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenify\DoctrineMigrations\OutputWriter;


final class SetConsoleOutputEventSubscriber implements EventSubscriberInterface
{

	/**
	 * @var OutputWriter
	 */
	private $outputWriter;


	public function __construct(OutputWriter $outputWriter)
	{
		$this->outputWriter = $outputWriter;
	}


	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [ConsoleEvents::COMMAND => 'setOutputWriter'];
	}


	public function setOutputWriter(ConsoleCommandEvent $event)
	{
		$command = $event->getCommand();
		if ( ! $this->isMigrationCommand($command)) {
			return;
		}

		$this->outputWriter->setConsoleOutput($event->getOutput());
	}


	/**
	 * @return bool
	 */
	private function isMigrationCommand(Command $command)
	{
		return $command instanceof AbstractCommand;
	}

}
