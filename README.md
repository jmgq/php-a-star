A Star algorithm for PHP
========================
[![Latest Stable Version](https://poser.pugx.org/jmgq/a-star/v/stable.svg)](https://packagist.org/packages/jmgq/a-star)
[![Build Status](https://travis-ci.org/jmgq/php-a-star.svg)](https://travis-ci.org/jmgq/php-a-star)
[![Coverage Status](https://coveralls.io/repos/jmgq/php-a-star/badge.png)](https://coveralls.io/r/jmgq/php-a-star)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jmgq/php-a-star/badges/quality-score.png)](https://scrutinizer-ci.com/g/jmgq/php-a-star)
[![SemVer](http://img.shields.io/:semver-2.0.0-brightgreen.svg)](http://semver.org/spec/v2.0.0.html)
[![License](https://poser.pugx.org/jmgq/a-star/license.svg)](https://packagist.org/packages/jmgq/a-star)

A Star pathfinding algorithm implementation for PHP.

Installation
------------
1. Install [composer](http://getcomposer.org/).

2. Add the A Star algorithm package to your `composer.json` file:
    ```
    "require": {
        ...
        "jmgq/a-star": "1.*"
        ...
    }
    ```

3. Update composer:
    ```sh
    composer update
    ```

Usage
-----
1. Create a class that implements the `Node` interface. The easiest option is to create a class that extends `AbstractNode`. It requires to implement the `getID` method:
    ```php
    use JMGQ\AStar\AbstractNode;
    
    class MyNode extends AbstractNode
    {
        // ...
        
        public function getID()
        {
            // Return a unique identifier for this node
        }
        
        // ...
    }
    ```

2. Extend the `AStar` class, which requires to implement its three abstract methods:
    ```php
    use JMGQ\AStar\AStar;
    
    class MyAStar extends AStar
    {
        // ...
        
        public function generateAdjacentNodes(Node $node)
        {
            // Return an array of adjacent nodes
        }
        
        public function calculateRealCost(Node $node, Node $adjacent)
        {
            // Return the actual cost between two adjacent nodes
        }
        
        public function calculateEstimatedCost(Node $start, Node $end)
        {
            // Return the heuristic estimated cost between the two given nodes
        }
        
        // ...
    }
    ```

3. That's all! You can now use the `run` method in the `AStar` class to generate the best path between two nodes. This method will return an ordered array of nodes, from the start node to the goal node. If there is no solution, an empty array will be returned.

Examples
--------
There are two working implementations in the `examples` folder.

### Terrain Example
In order to execute this example, run the following command:
```sh
php examples/Terrain/example.php
```

This example calculates the best route between two tiles in a rectangular board. Each tile has a cost associated to it, represented in a TerrainCost object. Every value in the TerrainCost array indicates the cost of entering into that particular tile.

For instance, given the following terrain:
```
  | 0 1 2 3
-----------
0 | 1 1 1 2
1 | 1 2 3 4
2 | 1 1 1 1
```

The cost to enter the tile `(1, 3)` (row 1, column 3) from any of its adjacent tiles is 4 units. So the real distance between `(0, 2)` and `(1, 3)` would be 4 units.

### Graph Example
In order to execute this example, run the following command:
```sh
php examples/Graph/example.php
```

Important notes:
- This example calculates the shortest path between two given nodes in a directed graph.
- A node's position is determined by its X and Y coordinates.
- The `Link` class specifies an arc (unidirectional connection) between two nodes. For instance `Link(A, B, D)` represents an arc from the node `A` to the node `B`, with a distance of `D` units.

Contributing
------------
Contributions to this project are always welcome. If you want to make a contribution, please fork the project, create a feature branch, and send a pull request.

### Development environment
In order to set up your development environment, please follow these steps:
1. Install [Docker](https://www.docker.com/).
2. Build the image: `docker build -t php-a-star .`
3. Run the image: `docker run -it php-a-star sh`

### Coding Standards
To ensure a consistent code base, please make sure your code follows the following conventions:
- The code should follow the standards defined in the [PSR-2](http://www.php-fig.org/psr/psr-2/) document.
- Use camelCase for naming variables, instead of underscores.
- Use parentheses when instantiating classes regardless of the number of arguments the constructor has.
- Write self-documenting code instead of actual comments (unless strictly necessary).

In other words, please imitate the existing code.

### Tests
This project has been developed following the [TDD](http://en.wikipedia.org/wiki/Test-driven_development) principles, and it strives for maximum test coverage. Therefore, you are encouraged to write tests for your new code. If your code is a bug fix, please write a test that proves that your code actually fixes the bug.

If you don't know how to write tests, please don't be discouraged, and send your pull request without tests, I will try to add them myself later.

### Contributors
Feel free to add yourself to the list of [contributors](CONTRIBUTORS.md).

Changelog
---------
Read the [changelog](CHANGELOG.md).
