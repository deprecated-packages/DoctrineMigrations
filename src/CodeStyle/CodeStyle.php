<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\CodeStyle;

use Zenify\DoctrineMigrations\Contract\CodeStyle\CodeStyleInterface;


final class CodeStyle implements CodeStyleInterface
{

	/**
	 * @var string
	 */
	const INDENTATION_TABS = 'tabs';

	/**
	 * @var string
	 */
	const INDENTATION_SPACES = 'spaces';

	/**
	 * @var string
	 */
	private $indentationStandard;


	/**
	 * @param string $indentationStandard
	 */
	public function __construct($indentationStandard)
	{
		$this->indentationStandard = $indentationStandard;
	}


	/**
	 * @param string $file
	 */
	public function applyForFile($file)
	{
		if ($this->indentationStandard === self::INDENTATION_TABS) {
			$this->convertSpacesToTabsForFile($file);
		}
	}


	/**
	 * @param string $file
	 */
	private function convertSpacesToTabsForFile($file)
	{
		$code = file_get_contents($file);
		$code = preg_replace('/ {4}/', "\t", $code);
		file_put_contents($file, $code);
	}

}
