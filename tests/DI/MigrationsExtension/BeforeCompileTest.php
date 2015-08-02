<?php

namespace Zenify\DoctrineMigrations\Tests\DI\MigrationsExtension;

use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use Nette\DI\ServiceDefinition;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Zenify\DoctrineMigrations\Configuration\Configuration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class BeforeCompileTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var MigrationsExtension
	 */
	private $extension;

	/**
	 * @var ContainerBuilder
	 */
	private $containerBuilder;


	protected function setUp()
	{
		$this->extension = new MigrationsExtension;

		$this->containerBuilder = new ContainerBuilder;
		$this->containerBuilder->parameters = ['appDir' => __DIR__];
		$this->containerBuilder->addDefinition('console', (new ServiceDefinition)->setClass(Application::class));

		$this->extension->setCompiler(new Compiler($this->containerBuilder), 'migrations');
		$this->extension->loadConfiguration();

		$this->containerBuilder->prepareClassList();
	}


	public function testSetConfigurationToCommands()
	{
		$this->extension->beforeCompile();

		$executeCommandDefinition = $this->getDefinitionByType(ExecuteCommand::class);

		$this->assertSame('setMigrationConfiguration', $executeCommandDefinition->getSetup()[0]->getEntity());
		$this->assertSame(['@' . Configuration::class], $executeCommandDefinition->getSetup()[0]->arguments);
	}


	public function testLoadCommandsToApplication()
	{
		$this->extension->beforeCompile();

		$applicationDefinition = $this->getDefinitionByType(Application::class);

		$this->assertCount(6, $applicationDefinition->getSetup());
		$this->assertSame('add', $applicationDefinition->getSetup()[0]->getEntity());
		$this->assertSame(['@' . ExecuteCommand::class], $applicationDefinition->getSetup()[0]->arguments);
	}


	/**
	 * @param string $type
	 * @return ServiceDefinition
	 */
	private function getDefinitionByType($type)
	{
		return $this->containerBuilder->getDefinition($this->containerBuilder->getByType($type));
	}

}
