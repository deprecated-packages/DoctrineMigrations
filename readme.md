# Zenify/DoctrineMigrations


Implementation of [Doctrine\Migrations](http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/) to Nette.

Tip: [Symfony bundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html) is more clear to read. 



## Installation

The best way to install is using [Composer](http://getcomposer.org/).

```sh
$ composer require zenify/doctrine-migrations:@dev
```

Register the extension in `config.neon`:

```yaml
extensions:
	migrations: Zenify\DoctrineMigrations\DI\MigrationsExtension
```


## Configuration

**config.neon** with default values

```
migrations:
	table: doctrine_migrations # database table for applied migrations
	dirs: # list of dirs to load migrations from
		- %appDir%/../migrations # first dir is used for generating migrations
	namespace: Migrations # namespace of migration classes
	enabled: FALSE # cli based loading; set TRUE to force loading in non-cli
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
