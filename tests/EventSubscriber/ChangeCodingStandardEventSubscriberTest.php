<?php

namespace Zenify\DoctrineMigrations\Tests\EventSubscriber;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


class ChangeCodingStandardEventSubscriberTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Application
	 */
	private $application;


	protected function setUp()
	{
		$container = (new ContainerFactory)->create();
		$this->application = $container->getByType(Application::class);
		$this->application->setAutoExit(FALSE);

		/** @var Configuration $configuration */
		$configuration = $container->getByType(Configuration::class);
		$configuration->setMigrationsDirectory($this->getMigrationsDirectory());
		@mkdir($this->getMigrationsDirectory());
	}


	public function testDispatchingGenerateCommand()
	{
		$input = new ArrayInput(['command' => 'migrations:generate']);
		$output = new BufferedOutput;

		$result = $this->application->run($input, $output);
		$this->assertSame(0, $result);
		$this->assertCommandOutputAndMigrationCodeStyle($output->fetch());
	}


	public function testDispatchingDiffCommand()
	{
		$input = new ArrayInput(['command' => 'migrations:diff']);
		$output = new BufferedOutput;

		$result = $this->application->run($input, $output);
		$this->assertSame(0, $result);
		$this->assertCommandOutputAndMigrationCodeStyle($output->fetch());
	}


	/**
	 * @param string $outputContent
	 */
	private function assertCommandOutputAndMigrationCodeStyle($outputContent)
	{
		$this->assertContains('Generated new migration class to', $outputContent);

		$migrationFile = $this->extractMigrationFile($outputContent);
		$fileContents = file_get_contents($migrationFile);
		$this->assertNotContains('    ', $fileContents);
		$this->assertContains(' ', $fileContents);
	}


	/**
	 * @return string
	 */
	private function getMigrationsDirectory()
	{
		return TEMP_DIR . '/Migrations';
	}


	/**
	 * @param string $outputContent
	 * @return string
	 */
	private function extractMigrationFile($outputContent)
	{
		preg_match('/"([^"]+)"/', $outputContent, $matches);
		return $matches[1];
	}

}
