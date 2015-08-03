<?php

namespace Zenify\DoctrineMigrations\Tests\CodeStyle;

use PHPUnit_Framework_TestCase;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;


class CodeStyleTest extends PHPUnit_Framework_TestCase
{

	public function testConvertToTabs()
	{
		$file = TEMP_DIR . '/some-spaced-text-file.txt';
		file_put_contents($file, '    hi');
		CodeStyle::convertSpacesToTabsForFile($file);

		$this->assertStringNotEqualsFile($file, '    hi');
		$this->assertStringEqualsFile($file, "\thi");
	}

}
