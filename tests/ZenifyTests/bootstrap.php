<?php

/** @var Composer\Autoload\ClassLoader $classLoader */
$classLoader = include __DIR__ . '/../../vendor/autoload.php';
$classLoader->addPsr4('ZenifyTests\\', __DIR__);


Tester\Environment::setup();
define('TEMP_DIR', createTempDir());
Tracy\Debugger::$logDirectory = TEMP_DIR;


/** @return string */
function createTempDir() {
	@mkdir(__DIR__ . '/../tmp'); // @ - directory may exists
	@mkdir($tempDir = __DIR__ . '/../tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
	Tester\Helpers::purge($tempDir);
	return realpath($tempDir);
}


$configurator = new Nette\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/config/default.neon');
return $configurator->createContainer();
