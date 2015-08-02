<?php

namespace Zenify\DoctrineMigrations\Tests\Configuration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\Version;
use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;
use Zenify\DoctrineMigrations\Tests\Configuration\ConfigurationSource\SomeService;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;
use Zenify\DoctrineMigrations\Tests\Migrations\Version123;


class ConfigurationTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Configuration
	 */
	private $configuration;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->configuration = $container->getByType(Configuration::class);
	}


	public function testCodingStandard()
	{
		$this->assertSame(MigrationsExtension::CODING_STANDARD_TABS, $this->configuration->getCodingStandard());

		$this->configuration->setCodingStandard(MigrationsExtension::CODING_STANDARD_SPACES);
		$this->assertSame(MigrationsExtension::CODING_STANDARD_SPACES, $this->configuration->getCodingStandard());
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

}
