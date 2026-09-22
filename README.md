# ik-oss/mezzio-debugbar

[![Software License][ico-license]](LICENSE)
[![CI][ico-ga]][link-ga]

Middleware to insert [PHP DebugBar](https://php-debugbar.com) automatically in html responses for the Mezzio framework.

Maintained by [INTERLIGENT kommunizieren GmbH][link-org]. This is a fork of
[mostafasy/mezzio-debugbar](https://github.com/mostafasy/mezzio-debugbar), which is no longer
maintained; that package is in turn a fork of
[php-middleware/phpdebugbar](https://github.com/php-middleware/phpdebugbar) and
[middlewares/debugbar](https://github.com/middlewares/debugbar).

## Requirements

* PHP 8.5

## Versions

This is the **1.x** maintenance line (`php-debugbar` ^2.2), maintained on the `1.0.x` branch.
New work goes to the 2.x line on `master`, which tracks `php-debugbar` 3.

## Installation
```
composer require --dev ik-oss/mezzio-debugbar
```
## Example

This package supplies a config provider, which could be added to your config/config.php when using laminas-config-aggregator or mezzio-config-manager. However, because it should only be enabled in development, we recommend creating a "local" configuration file (e.g., config/autoload/php-debugbar.local.php) when you need to enable it, with the following contents:

```php
use DebugBar\Bridge\DoctrineCollector;
use DebugBar\Storage\FileStorage;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\Stdlib\ArrayUtils;

$aggregator = new ConfigAggregator(
    [
        Ikoss\Mezzio\DebugBar\ConfigProvider::class,
    ]
);
return ArrayUtils::merge(
    $aggregator->getMergedConfig(),
    [
// here you can overload the default Values .as example add doctrine collector or fileStorage
        'debugbar'     => [
            'disable'    => false,
            'captureAjax' => true,
            'collectors' => [
                DoctrineCollector::class,
            ],
            'storage'    => FileStorage::class,
            'storage_dir' =>'path/to-your-storage-dir'
        ],
    ]
);
```
### Disable config
Sometimes you want to have control when enable or disable PHP Debug Bar:
- We allow you to disable attaching phpdebugbar using X-Disable-Debug-Bar: true  header, cookie or request attribute. 
- or you can configure in config:
```
'disable'=>true
```
### captureAjax conifg

Use this option to capture ajax requests and send the data in the headers. [More info about AJAX and Stacked data](http://phpdebugbar.com/docs/ajax-and-stack.html#ajax-and-stacked-data). By default it's disabled.

### inline

Set true to dump the js/css code inline in the html. This fixes (or mitigate) some issues related with loading the debugbar assets.

### renderOptions

Use this option to pass  render options to the debugbar as an array. A list of available options can be found at https://github.com/maximebf/php-debugbar/blob/master/src/DebugBar/JavascriptRenderer.php#L132

An example usage would be to pass a new location for the ``base_url`` so that you can rewrite the location of the files needed to render the debug bar. This can be used with symlinks, .htaccess or routes to the files to ensure the debugbar files are accessible.

### File Storage 

It will collect data as json files under the specified directory (which has to be writable).you can configure as :             
```
'storage'    => FileStorage::class,
'storage_dir' =>'path/to-your-storage-dir'
```
## pdo Storage 
It will collect data and saved to database you can configure as :   
```
'storage'    => PdoStorage::class,
'pdo' =>[
  'dsn'=>'mysql:dbname=testdb;host=127.0.0.1';',
  'username'=>'dbuser',
  'password'=>'dbpass',
],
```
please note you have to execute sql schema [pdo-sql-Schema]


## Doctrine Storage

It will collect data and saved to database by using Doctrine you can configure as :   
```
'storage'    => DoctrineStorage::class,
  'doctrine_storage'=>[
    // it will save queries into extra table for analysis purpose.by default it is false.
    'save_sql_queries_to_extra_table' => true,
    ],

```
you have to execute sql schema: [doctrine-sql-Schema]

`DoctrineStorage` works with both `doctrine/dbal` 3 and 4.

---

## Doctrine Collector

> **Note**
> The Doctrine collector in the 1.x line relies on `Doctrine\DBAL\Logging\DebugStack`, which was
> removed in `doctrine/dbal` 4. It therefore requires `doctrine/dbal` ^3. The 2.x line reimplements
> the collector on top of `php-debugbar/doctrine-bridge`, which works with dbal 3 and 4.

---

## Route Colloctor

It will collect the Route Informations.
we can add the collectors to config as the following:
```
  'collectors' => [
                RouteCollector::class,
            ],

```
Example of Route config:

```
 'routes' => [
        [
            'path' => '/path/to/match',
            'middleware' => 'Middleware service or pipeline',
            'allowed_methods' => ['GET', 'POST', 'PATCH'],
            'name' => 'route.name',
            'options' => [
                'stuff' => 'to',
                'pass'  => 'to',
                'the'   => 'underlying router',
            ],
        ],
        'another.route.name' => [
            'path' => '/another/path/to/match',
            'middleware' => 'Middleware service or pipeline',
            'allowed_methods' => ['GET', 'POST'],
            'options' => [
                'more'    => 'router',
                'options' => 'here',
            ],
        ],
    ],

```
---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ga]: https://github.com/INTERLIGENT-kommunzieren-GmbH/mezzio-debugbar/actions/workflows/ci.yml/badge.svg

[link-ga]: https://github.com/INTERLIGENT-kommunzieren-GmbH/mezzio-debugbar/actions/workflows/ci.yml
[link-org]: https://github.com/INTERLIGENT-kommunzieren-GmbH
[pdo-sql-Schema]: https://github.com/INTERLIGENT-kommunzieren-GmbH/mezzio-debugbar/blob/master/src/Storage/DatabaseSchemaSql/pdo_storge_sql_schema.sql
[doctrine-sql-Schema]: https://github.com/INTERLIGENT-kommunzieren-GmbH/mezzio-debugbar/blob/master/src/Storage/DatabaseSchemaSql/doctrine_storge_sql_schema.sql
