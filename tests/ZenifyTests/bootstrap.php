<?php

if (@ ! include __DIR__ . '/../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

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


//$_SERVER = array_intersect_key($_SERVER, array_flip(array(
//	'PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv')));
//$_SERVER['REQUEST_TIME'] = 1234567890;
//$_ENV = $_GET = $_POST = array();


function run(Tester\TestCase $testCase) {
	$testCase->run();
}


$configurator = new Nette\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/config/default.neon');
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register(TRUE);

return $configurator->createContainer();
