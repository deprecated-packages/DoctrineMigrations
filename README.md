# Doctrine Migrations

[![Build Status](https://img.shields.io/travis/Zenify/DoctrineMigrations.svg?style=flat-square)](https://travis-ci.org/Zenify/DoctrineMigrations)
[![Quality Score](https://img.shields.io/scrutinizer/g/Zenify/DoctrineMigrations.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineMigrations)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Zenify/DoctrineMigrations.svg?style=flat-square)](https://scrutinizer-ci.com/g/Zenify/DoctrineMigrations)
[![Downloads this Month](https://img.shields.io/packagist/dm/zenify/doctrine-migrations.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-migrations)
[![Latest stable](https://img.shields.io/packagist/v/zenify/doctrine-migrations.svg?style=flat-square)](https://packagist.org/packages/zenify/doctrine-migrations)

Implementation of [Doctrine\Migrations](http://docs.doctrine-project.org/projects/doctrine-migrations/en/latest/) to Nette.


## Install

Install via Composer:

```sh
composer require zenify/doctrine-migrations
```

Register extensions in `config.neon` (includes [Kdyby/Doctrine](https://github.com/kdyby/doctrine) configuration):

```yaml
extensions:
    - Kdyby\Annotations\DI\AnnotationsExtension
    - Kdyby\Events\DI\EventsExtension
    - Kdyby\Console\DI\ConsoleExtension
    doctrine: Kdyby\Doctrine\DI\OrmExtension
    migrations: Zenify\DoctrineMigrations\DI\MigrationsExtension

doctrine:
	host: localhost
	user: root
	password: 
	dbname: database
```


## Configuration

`config.neon` with default values

```yaml
migrations:
	table: doctrine_migrations # database table for applied migrations
	dirs: # list of dirs to load migrations from
		- %appDir%/../migrations # first dir is used for generating migrations
	namespace: Migrations # namespace of migration classes
	codingStandard: tabs # or "spaces", cs for generated classes
```


## Usage

Open your CLI and run command:

```sh
php www/index.php
```

And then you should see all available commands:

![CLI commands](cli-commands.png)


And then you can run any command you need, e.g. migrate command:

```sh
php www/index.php migrations:migrate
```

If you get lost, just use `-h` option for help:

```sh
php www/index.php migrations:migrate -h
```


For further use, please check [docs in Symfony bundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html).


## Features


### Multiple directories

In case you have plenty of migrations and you want to store them in another directory, just add them to `dirs`.

Only the first one (here `%appDir%/../migrations`) will be used to create migrations from command line. 

```yaml
migrations:
	dirs:
		- %appDir%/../migrations
		- %appDir%/../migrations/2013
		- %appDir%/../migrations/2012
```


### Injected migrations

```php
namespace Migrations;


class Version20140801152432 extends AbstractMigration
{

	/**
	 * @inject
	 * @var Doctrine\ORM\EntityManagerInterface
	 */
	public $entityManager;


	public function up(Schema $schema)
	{
		$product = new Product;
		$product->setName('Chips without fish')
		$this->entityManager->persist(product);
		$this->entityManager->flush();
	}

	// ...

}
```
