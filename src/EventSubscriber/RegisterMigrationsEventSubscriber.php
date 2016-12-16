<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\EventSubscriber;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


final class RegisterMigrationsEventSubscriber implements EventSubscriberInterface
{

	/**
	 * @var Configuration
	 */
	private $configuration;


	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
	}


	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [ConsoleEvents::COMMAND => 'registerMigrations'];
	}


	public function registerMigrations(ConsoleCommandEvent $event)
	{
		$command = $event->getCommand();
		if ( ! $this->isMigrationCommand($command)) {
			return;
		}

		$this->configuration->registerMigrationsFromDirectory(
			$this->configuration->getMigrationsDirectory()
		);
	}


	/**
	 * @return bool
	 */
	private function isMigrationCommand(Command $command)
	{
		return $command instanceof AbstractCommand;
	}

}
