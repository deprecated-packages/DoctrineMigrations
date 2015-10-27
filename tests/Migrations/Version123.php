<?php

namespace Zenify\DoctrineMigrations\Tests\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Zenify\DoctrineMigrations\Tests\Configuration\ConfigurationSource\SomeService;


final class Version123 extends AbstractMigration
{

	/**
	 * @inject
	 * @var SomeService
	 */
	public $someService;


	/**
	 * {@inheritdoc}
	 */
	public function up(Schema $schema)
	{
		$this->addSql('CREATE TABLE "category" ( "id" integer NOT NULL );');
	}


	/**
	 * {@inheritdoc}
	 */
	public function down(Schema $schema)
	{
	}

}
