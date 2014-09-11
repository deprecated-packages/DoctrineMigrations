# Zenify/DoctrineMigrations

[![Build Status](https://travis-ci.org/Zenify/DoctrineMigrations.svg?branch=master)](https://travis-ci.org/Zenify/DoctrineMigrations)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-migrations.svg)](https://packagist.org/packages/zenify/doctrine-migrations)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-migrations.svg)](https://packagist.org/packages/zenify/doctrine-migrations)


Implementation of [Doctrine\Migrations](http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/) to Nette.

Tip: [Symfony bundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html) is more clear to read than Doctrine doc.



## Installation

The best way to install is using [Composer](http://getcomposer.org/).

Add to your `composer.json`:

```yaml
"require": {
	"doctrine/migrations": "@dev"
	"zenify/doctrine-migrations": "@dev"
}
```

and run:

```sh
$ composer update
```

Register the extension in `config.neon`:

```yaml
extensions:
	migrations: Zenify\DoctrineMigrations\DI\MigrationsExtension
```


## Configuration

**config.neon** with default values

```yaml
migrations:
	table: doctrine_migrations # database table for applied migrations
	dirs: # list of dirs to load migrations from
		- %appDir%/../migrations # first dir is used for generating migrations
	namespace: Migrations # namespace of migration classes
	enabled: FALSE # cli based loading; set TRUE to force loading in non-cli
	codingStandard: tabs # or "spaces", cs for generated classes
```


## Features


### Injected migrations

```php
namespace Migrations;


class Version20140801152432 extends AbstractMigration
{
	/**
	 * @inject
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	public $em;


	public function up(Schema $schema)
	{
		$product = new Product;
		$product->setName('Chips without fish')
		$this->em->persist(product);

		$this->em->flush();
	}

	// ...

}
```


### Customize coding standard

Files with migrations respects [PGS-2](php-guidelines.github.io/pgs-2/), oppose to origin [PSR-2](http://www.php-fig.org/psr/psr-2/).
That means you can set both tabs or spaces as coding standard for generated migrations classes.
