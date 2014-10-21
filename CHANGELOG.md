Changelog
=========

### Next release
- [Change] Removed the addChild and getChildren method signatures from the Node interface.
- [New] Added a Contributors file.
- [Devel] [New] Added Scrutinizer support.

### 1.1.1 (2014-10-14)
- [Bugfix] Fixed an infinite loop bug when getting a solution (path) that contains a starting node with a circular reference to its parent.
- [Devel] [Bugfix] Fixed a PHPUnit issue caused by BaseAStarTest not being abstract.
- [Devel] [New] Added a Contributing section to the Readme.

### 1.1.0 (2014-06-03)
- [New] Added a new example (Graph).
- [Bugfix] When the node being evaluated is found in the open or closed list, the algorithm checks its tentative G value (rather than its F value) in order to determine if the node has a better cost.
- [Improv] Removed an unnecessary step in the algorithm.
- [Devel] [New] Added Travis support (Continuous Integration server).
- [Devel] [New] Added Coveralls support.
- [Devel] [New] Added a required minimum PHP version (5.3.0).
- [Devel] [Improv] Increased the code coverage.

### 1.0.0 (2014-05-21)
- Initial release.
