<?php

namespace Zenify\DoctrineMigrations\Tests\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


final class Version456 extends AbstractMigration
{

	/**
	 * {@inheritdoc}
	 */
	public function up(Schema $schema)
	{
		$this->addSql('CREATE TABLE "product" ( "id" integer NOT NULL );');
	}


	/**
	 * {@inheritdoc}
	 */
	public function down(Schema $schema)
	{
	}

}
