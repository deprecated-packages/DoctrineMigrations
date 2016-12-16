<?php

include __DIR__ . '/../vendor/autoload.php';


$tempDir = __DIR__ . '/temp/' . getmypid();
@mkdir($tempDir, 0777, TRUE);
@mkdir($tempDir . '/log', 0777, TRUE);
@mkdir($tempDir . '/Migrations', 0777, TRUE);

register_shutdown_function(function () {
	Nette\Utils\FileSystem::delete(__DIR__ . '/temp');
});
