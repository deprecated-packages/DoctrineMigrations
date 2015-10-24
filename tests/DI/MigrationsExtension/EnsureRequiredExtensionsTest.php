<?php

namespace Zenify\DoctrineMigrations\Tests\DI\MigrationsExtension;

use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\Exception\DI\MissingExtensionException;
use Zenify\DoctrineMigrations\Tests\ContainerFactory;


final class EnsureRequiredExtensionsTest extends PHPUnit_Framework_TestCase
{

	public function testEnsureSymnediEventDispatcher()
	{
		$this->setExpectedException(MissingExtensionException::class);
		(new ContainerFactory)->createWithConfig(__DIR__ . '/../../config/extensionOnly.neon');
	}

}
