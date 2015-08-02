<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2014 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineMigrations\Configuration;

use Doctrine\DBAL\Migrations\Configuration\Configuration as BaseConfiguration;
use Zenify\DoctrineMigrations\DI\MigrationsExtension;


class Configuration extends BaseConfiguration
{

	/**
	 * @var string
	 */
	private $codingStandard = MigrationsExtension::CODING_STANDARD_TABS;


	/**
	 * @param string $codingStandard
	 */
	public function setCodingStandard($codingStandard)
	{
		$this->codingStandard = $codingStandard;
	}


	/**
	 * @return string
	 */
	public function getCodingStandard()
	{
		return $this->codingStandard;
	}

}
