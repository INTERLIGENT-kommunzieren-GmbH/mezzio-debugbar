# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Changed
- Package renamed from `mostafasy/mezzio-debugbar` to `ik-oss/mezzio-debugbar`; maintenance moved
  to INTERLIGENT kommunizieren GmbH after the upstream project was abandoned.
- **BC break:** the PHP namespace moved from `Mezzio\DebugBar\` to `Ikoss\Mezzio\DebugBar\`.
  Every reference has to be updated, including `Mezzio\DebugBar\ConfigProvider::class` in
  `config/config.php` and any service names pinned to the old namespace in local config.
- Minimum PHP version raised to 8.5.
- Test suite upgraded to PHPUnit 12 (`returnValueMap()` replaced by `willReturnMap()`, mocks
  without expectations replaced by stubs, dynamic properties declared).
- Dev dependencies upgraded: laminas-coding-standard 3, PHPStan 2, laminas-diactoros 3.
- CI workflow rewritten for PHP 8.5; now also runs PHPStan and a `--prefer-lowest` job.

### Added
- `phpstan.neon.dist`, so the existing `composer phpstan` script actually has a configuration.

### Fixed
- `MonologCollectorFactory` and `SymfonyMailCollectorFactory` were committed without a `.php`
  extension and could never be autoloaded.
- `DoctrineStorage` passed parameters to `Statement::executeStatement()`/`executeQuery()`, which
  is not supported from doctrine/dbal 3 onwards; it now binds them via the connection.
- `RouteCollector` redeclared `$useHtmlVarDumper`, `useHtmlVarDumper()` and
  `isHtmlVarDumperUsed()`, which are already provided by `DebugBar\DataFormatter\HasDataFormatter`.

## [2.1.0] - 2021-07-10
### Added
- New option `renderOptions` [#12].

## [2.0.1] - 2020-12-02
### Added
- Support for PHP 8

## [2.0.0] - 2019-12-01
### Added
- Added a second argument to the constructor to set a `responseFactory`
- Added a third argument to the constructor to set a `streamFactory`

### Removed
- Support for PHP 7.0 and 7.1
- Options `responseFactory` and `streamFactory`. Use the constructor arguments.

## [1.1.0] - 2018-08-04
### Added
- PSR-17 support
- New option `responseFactory`
- New option `streamFactory`

## [1.0.0] - 2018-01-25
### Added
- Improved testing and added code coverage reporting
- Added tests for PHP 7.2

### Changed
- Upgraded to the final version of PSR-15 `psr/http-server-middleware`

### Fixed
- Updated license year

## [0.5.0] - 2017-11-13
### Changed
- Replaced `http-interop/http-middleware` with  `http-interop/http-server-middleware`.

### Removed
- Removed support for PHP 5.x.

## [0.4.0] - 2017-09-21
### Added
- New option `inline()` to embed the code inline in the html

### Changed
- Append `.dist` suffix to phpcs.xml and phpunit.xml files
- Changed the configuration of phpcs and php_cs
- Upgraded phpunit to the latest version and improved its config file
- Updated to `http-interop/http-middleware#0.5`

## [0.3.2] - 2017-03-28
### Fixed
- Fix `Content-Length` header

## [0.3.1] - 2017-03-08
### Fixed
- Null reference bug

## [0.3.0] - 2016-12-26
### Changed
- Updated tests
- Updated to `http-interop/http-middleware#0.4`
- Updated `friendsofphp/php-cs-fixer#2.0`

## [0.2.0] - 2016-11-27
### Changed
- Updated to `http-interop/http-middleware#0.3`

## 0.1.0 - 2016-10-08
First version

[#12]: https://github.com/middlewares/debugbar/issues/12

[2.1.0]: https://github.com/middlewares/debugbar/compare/v2.0.1...v2.1.0
[2.0.1]: https://github.com/middlewares/debugbar/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/middlewares/debugbar/compare/v1.1.0...v2.0.0
[1.1.0]: https://github.com/middlewares/debugbar/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/middlewares/debugbar/compare/v0.5.0...v1.0.0
[0.5.0]: https://github.com/middlewares/debugbar/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/middlewares/debugbar/compare/v0.3.2...v0.4.0
[0.3.2]: https://github.com/middlewares/debugbar/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/middlewares/debugbar/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/middlewares/debugbar/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/debugbar/compare/v0.1.0...v0.2.0
