<?php

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Contract\CodeStyle;


interface CodeStyleInterface
{

	/**
	 * @param string $file
	 */
	function applyForFile($file);

}
