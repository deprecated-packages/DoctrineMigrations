<?php

declare(strict_types=1);

/*
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Contract\CodeStyle;


interface CodeStyleInterface
{

	public function applyForFile(string $file);

}
