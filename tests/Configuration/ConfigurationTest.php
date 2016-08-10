<?php

namespace Zenify\DoctrineMigrations\Tests\Configuration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Version;
use Nette\DI\Container;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\Configuration\Configuration as ZenifyConfiguration;
use Zenify\DoctrineMigrations\Exception\Configuration\MigrationClassNotFoundException;
use Zenify\DoctrineMigrations\Tests\Configuration\ConfigurationSource\SomeService;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;
use Zenify\DoctrineMigrations\Tests\Migrations\Version123;


final class ConfigurationTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Configuration
	 */
	private $configuration;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->configuration = $container->getByType(Configuration::class);

		$this->configuration->registerMigrationsFromDirectory(
			$this->configuration->getMigrationsDirectory()
		);
	}


	public function testInject()
	{
		$migrations = $this->configuration->getMigrationsToExecute('up', 123);
		$this->assertCount(1, $migrations);

		/** @var Version $version */
		$version = $migrations[123];
		$this->assertInstanceOf(Version::class, $version);

		/** @var AbstractMigration|Version123 $migration */
		$migration = $version->getMigration();
		$this->assertInstanceOf(AbstractMigration::class, $migration);

		$this->assertInstanceOf(SomeService::class, $migration->someService);
	}


	public function testCreateDirectoryOnSet()
	{
		$migrationsDir = TEMP_DIR . '/migrations';
		$this->assertFileNotExists($migrationsDir);
		$this->configuration->setMigrationsDirectory($migrationsDir);
		$this->assertFileExists($migrationsDir);
	}


	public function testLoadMigrationsFromSubdirs()
	{
		$migrations = $this->configuration->getMigrations();
		$this->assertCount(2, $migrations);
	}

}
