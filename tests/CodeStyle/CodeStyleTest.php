<?php

namespace Zenify\DoctrineMigrations\Tests\CodeStyle;

use PHPUnit\Framework\TestCase;
use Zenify\DoctrineMigrations\CodeStyle\CodeStyle;


final class CodeStyleTest extends TestCase
{

	public function testConvertToTabs()
	{
		$file = sys_get_temp_dir() . '/doctrine-migrations/some-spaced-text-file.txt';
		file_put_contents($file, '    hi');
		(new CodeStyle(CodeStyle::INDENTATION_TABS))->applyForFile($file);

		$this->assertStringNotEqualsFile($file, '    hi');
		$this->assertStringEqualsFile($file, "\thi");
	}


	public function testKeepSpaces()
	{
		$file = sys_get_temp_dir() . '/doctrine-migrations/some-spaced-text-file.txt';
		file_put_contents($file, '    hi');
		(new CodeStyle(CodeStyle::INDENTATION_SPACES))->applyForFile($file);

		$this->assertStringEqualsFile($file, '    hi');
		$this->assertStringNotEqualsFile($file, "\thi");
	}

}
