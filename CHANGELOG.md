# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Added a Contributors file.
- Added Scrutinizer support.
- Added Travis builds for PHP 7.0, 7.1 and 7.2.

### Changed
- Enforced the PSR-2 standards by checking them with CodeSniffer during the Travis build.
- Updated the Changelog format.
- Updated the development dependencies in composer.

### Removed
- Removed the `addChild` and `getChildren` method signatures from the `Node` interface.

### Fixed
- Fixed Travis build for PHP 5.3.
- The tests and examples now use the `autoload-dev` section instead of `autoload` in `composer.json`.

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

[Unreleased]: https://github.com/jmgq/php-a-star/compare/v1.1.0...HEAD
[1.1.1]: https://github.com/jmgq/php-a-star/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/jmgq/php-a-star/compare/v1.0.0...v1.1.0
