# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.1.1] - 2022-01-02
### Added
- Added a new static analysis tool: Phan.
- Added a new static analysis tool: PHPStan.
- Added a new static analysis tool: Psalm.

### Changed
- Replaced Travis CI with GitHub Actions.
- Marked `NodeCollectionInterface` and `NodeHashTable` as internal.

### Fixed
- Fixed `NodeHashTable::getIterator()` return type.

## [2.1.0] - 2021-02-09
### Added
- Added a `NodeIdentifierInterface` to allow the user to specify unique node IDs.

## [2.0.0] - 2021-02-08
### Changed
- Raised the minimum required version of PHP to 8.0.
- Removed support for HHVM.
- Changed the library's public API (**breaking change**).
- Switched the coding standards from PSR-2 to PSR-12.

## [1.2.0] - 2021-02-03
### Added
- Added a composer script for the unit tests and code coverage.
- Added a composer script for the coding standards.
- Added a composer script for the graph example.
- Added a composer script for the terrain example.
- Added Travis build for PHP 7.3.
- Added a benchmark utility.

### Changed
- Removed `prestissimo` from the [Dockerfile](Dockerfile).

### Fixed
- Fixed Travis build for PHP 5.4 and 5.5.
- Fixed one test that had an ambiguous solution.

## [1.1.2] - 2018-06-03
### Added
- Added a Contributors file.
- Added Scrutinizer support.
- Added Travis builds for PHP 7.0, 7.1 and 7.2.
- Added support for Docker.

### Changed
- Enforced the PSR-2 standards by checking them with CodeSniffer during the Travis build.
- Updated the Changelog format.
- Updated the development dependencies in composer.
- Simplified the installation instructions.

### Deprecated
- Deprecated the `addChild` and `getChildren` method signatures from the `Node` interface.

### Fixed
- Fixed Travis build for PHP 5.3.
- The tests and examples now use the `autoload-dev` section instead of `autoload` in `composer.json`.
- Fixed Coveralls not working due to the `src_dir` parameter being removed in the current version.

## [1.1.1] - 2014-10-14
### Added
- Added a Contributing section to the Readme.

### Fixed
- Fixed an infinite loop bug when getting a solution (path) that contains a starting node with a circular reference to its parent.
- Fixed a PHPUnit issue caused by `BaseAStarTest` not being abstract.

## [1.1.0] - 2014-06-03
### Added
- Added a new example (Graph).
- Added Travis support (Continuous Integration server).
- Added Coveralls support.
- Added a required minimum PHP version (5.3.0).

### Changed
- Removed an unnecessary step in the algorithm.
- Increased the code coverage.

### Fixed
- When the node being evaluated is found in the open or closed list, the algorithm checks its tentative G value (rather than its F value) in order to determine if the node has a better cost.

## 1.0.0 - 2014-05-21
### Added
- Initial release.

[Unreleased]: https://github.com/jmgq/php-a-star/compare/v2.1.1...HEAD
[2.1.1]: https://github.com/jmgq/php-a-star/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/jmgq/php-a-star/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/jmgq/php-a-star/compare/v1.2.0...v2.0.0
[1.2.0]: https://github.com/jmgq/php-a-star/compare/v1.1.2...v1.2.0
[1.1.2]: https://github.com/jmgq/php-a-star/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/jmgq/php-a-star/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/jmgq/php-a-star/compare/v1.0.0...v1.1.0
