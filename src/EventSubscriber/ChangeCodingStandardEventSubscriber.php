<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\EventSubscriber;

use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class ChangeCodingStandardEventSubscriber implements EventSubscriberInterface
{

	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * @var CodeStyle
	 */
	private $codeStyle;


	public function __construct(Configuration $configuration, CodeStyle $codeStyle)
	{
		$this->configuration = $configuration;
		$this->codeStyle = $codeStyle;
	}


	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return [ConsoleEvents::TERMINATE => 'changeCodingStandard'];
	}


	public function changeCodingStandard(ConsoleTerminateEvent $event)
	{
		if ($this->configuration->getCodingStandard() === MigrationsExtension::CODING_STANDARD_SPACES) {
			return;
		}

		$command = $event->getCommand();
		if ( ! $this->isAllowedCommand($command->getName())) {
			return;
		}

		$filename = $this->getCurrentMigrationFileName();
		if (file_exists($filename)) {
			$this->codeStyle->convertSpacesToTabsForFile($filename);
		}
	}


	/**
	 * @param string $name
	 * @return bool
	 */
	private function isAllowedCommand($name)
	{
		return in_array($name, ['migrations:generate', 'migrations:diff']);
	}


	/**
	 * @return string
	 */
	private function getCurrentMigrationFileName()
	{
		$version = $this->getCurrentVersionName();

		$i = 0;
		while ( ! file_exists($this->getMigrationFileByVersion($version)) && $i <= 10) {
			$version--;
			$i++;
		}

		$path = $this->getMigrationFileByVersion($version);
		return $path;
	}


	/**
	 * @return string
	 */
	private function getCurrentVersionName()
	{
		return date('YmdHis');
	}


	/**
	 * @param string $version
	 * @return string
	 */
	private function getMigrationFileByVersion($version)
	{
		return $this->configuration->getMigrationsDirectory() . '/Version' . $version . '.php';
	}

}
