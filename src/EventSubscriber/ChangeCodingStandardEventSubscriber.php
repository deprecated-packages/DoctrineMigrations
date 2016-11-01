<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\EventSubscriber;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\MigrationDirectoryHelper;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenify\DoctrineMigrations\Contract\CodeStyle\CodeStyleInterface;


final class ChangeCodingStandardEventSubscriber implements EventSubscriberInterface
{

	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * @var CodeStyleInterface
	 */
	private $codeStyle;


	public function __construct(Configuration $configuration, CodeStyleInterface $codeStyle)
	{
		$this->codeStyle = $codeStyle;
		$this->configuration = $configuration;
	}


	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [ConsoleEvents::TERMINATE => 'applyCodingStyle'];
	}


	public function applyCodingStyle(ConsoleTerminateEvent $event)
	{
		$command = $event->getCommand();
		if ( ! $this->isAllowedCommand($command->getName())) {
			return;
		}

		$filename = $this->getCurrentMigrationFileName();
		if (file_exists($filename)) {
			$this->codeStyle->applyForFile($filename);
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
		$migrationDirectoryHelper = new MigrationDirectoryHelper($this->configuration);
		return $migrationDirectoryHelper->getMigrationDirectory() . '/Version' . $version . '.php';
	}

}
