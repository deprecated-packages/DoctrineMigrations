<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\CodeStyle;


class CodeStyle
{

	/**
	 * @param string $file
	 */
	public function convertSpacesToTabsForFile($file)
	{
		$code = file_get_contents($file);
		$code = preg_replace("/ {4}/", "\t", $code);
		file_put_contents($file, $code);
	}

}
