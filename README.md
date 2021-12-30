A Star algorithm for PHP
========================
[![Latest Stable Version](https://poser.pugx.org/jmgq/a-star/v/stable.svg)](https://packagist.org/packages/jmgq/a-star)
[![Static Analysis](https://github.com/jmgq/php-a-star/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/jmgq/php-a-star/actions/workflows/static-analysis.yml)
[![Tests](https://github.com/jmgq/php-a-star/actions/workflows/tests.yml/badge.svg)](https://github.com/jmgq/php-a-star/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/jmgq/php-a-star/badge.svg)](https://coveralls.io/github/jmgq/php-a-star)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jmgq/php-a-star/badges/quality-score.png)](https://scrutinizer-ci.com/g/jmgq/php-a-star)
[![SemVer](https://img.shields.io/:semver-2.0.0-brightgreen.svg)](https://semver.org/spec/v2.0.0.html)
[![License](https://poser.pugx.org/jmgq/a-star/license.svg)](https://packagist.org/packages/jmgq/a-star)

A Star pathfinding algorithm implementation for PHP.

Requirements
------------
You need PHP >= 8.0 to use this library, but the latest stable version of PHP is recommended.

If you need to run this library on an older version of PHP (or HHVM), please install a 1.x version.

Installation
------------
1. Install [composer](https://getcomposer.org/).

2. Add the A Star algorithm package to your `composer.json` file and download it:
    ```sh
    composer require jmgq/a-star
    ```

Usage
-----
1. Create a class that implements `DomainLogicInterface`. The parameters of the three methods in this interface are nodes. A node can be of any type: it could be a string, an integer, an object, etc. You decide the shape of a node, depending on your business logic. You can optionally provide a way to identify your nodes ([check why and how](#specifying-the-unique-node-id)).
    ```php
    use JMGQ\AStar\DomainLogicInterface;

    class DomainLogic implements DomainLogicInterface
    {
        // ...

        public function getAdjacentNodes(mixed $node): iterable
        {
            // Return a collection of adjacent nodes
        }

        public function calculateRealCost(mixed $node, mixed $adjacent): float | int
        {
            // Return the actual cost between two adjacent nodes
        }

        public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float | int
        {
            // Return the heuristic estimated cost between the two given nodes
        }

        // ...
    }
    ```

2. Instantiate the `AStar` class, which requires the newly created Domain Logic object:
    ```php
    use JMGQ\AStar\AStar;

    $domainLogic = new DomainLogic();

    $aStar = new AStar($domainLogic);
    ```

3. That's all! You can now use the `run` method in the `AStar` class to generate the best path between two nodes. This method will return an ordered list of nodes, from the start node to the goal node. If there is no solution, an empty list will be returned.
    ```php
    $solution = $aStar->run($start, $goal);
    ```

### Specifying the unique node ID
In order to work correctly, the A* algorithm needs to uniquely identify each node. This library will automatically generate a default ID for each node, which will be the result of serialising the node with PHP's [serialize](https://www.php.net/manual/function.serialize.php) function. This has two major disadvantages:
1. **It is not always correct**: for instance, let's assume that a node is represented by an associative array with two keys: `x` and `y`. The following two nodes are the same, but their serialised value is not:
    ```php
    $node1 = ['x' => 4, 'y' => 5];
    $node2 = ['y' => 5, 'x' => 4];
    serialize($node1); // a:2:{s:1:"x";i:4;s:1:"y";i:5;}
    serialize($node2); // a:2:{s:1:"y";i:5;s:1:"x";i:4;}
    ```
2. **Performance issues**: if the node structure is very complex, serialising it could take too long.

Rather than relying on this default mechanism, you can avoid the serialisation process and instead provide the node ID yourself, by ensuring that your node implements `NodeIdentifierInterface`, which only declares one method:
```php
interface NodeIdentifierInterface
{
    public function getUniqueNodeId(): string;
}
```

For instance, this is how it has been implemented in the Terrain example:
```php
use JMGQ\AStar\Node\NodeIdentifierInterface;

class Position implements NodeIdentifierInterface
{
    private int $row;
    private int $column;

    // ...

    public function getUniqueNodeId(): string
    {
        return $this->row . 'x' . $this->column;
    }

    // ...
}
```

Examples
--------
There are two working implementations in the [examples](examples) folder.

### Terrain Example
In order to execute this example, run the following command:
```sh
composer example:terrain
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
composer example:graph
```

Important notes:
- This example calculates the shortest path between two given nodes in a directed graph.
- A node's position is determined by its X and Y coordinates.
- The `Link` class specifies an arc (unidirectional connection) between two nodes. For instance `Link(A, B, D)` represents an arc from the node `A` to the node `B`, with a distance of `D` units.

Benchmark
---------
This project contains a benchmark utility that can be used to test the algorithm's efficiency. This can be particularly useful to evaluate any changes made to the algorithm. The benchmark runs against the Terrain example.

To execute it with the default parameters, simply run:
```sh
composer benchmark
```

For a full list of parameters, please run:
```sh
composer benchmark help benchmark
```

For instance, the following command runs the algorithm against 10 different terrains of size 5x5, another 10 different terrains of size 12x12, and it uses 123456 as its seed to randomly generate the costs of each one of the terrain tiles:
```sh
composer benchmark -- --iterations=10 --size=5 --size=12 --seed=123456
```

Contributing
------------
Contributions to this project are always welcome. If you want to make a contribution, please fork the project, create a feature branch, and send a pull request.

### Development environment
In order to set up your development environment, please follow these steps:
1. Install [Docker](https://www.docker.com/).
2. Build the image: `docker build -t php-a-star .`
3. Run the image:
    ```sh
    docker run -it \
        --mount type=bind,source="$(pwd)",target=/opt/php-a-star \
        php-a-star \
        sh
    ```

### Coding Standards
To ensure a consistent code base, please make sure your code follows the following conventions:
- The code should follow the standards defined in the [PSR-12](https://www.php-fig.org/psr/psr-12/) document.
- Use camelCase for naming variables, instead of underscores.
- Use parentheses when instantiating classes regardless of the number of arguments the constructor has.
- Write self-documenting code instead of actual comments (unless strictly necessary).

In other words, please imitate the existing code.

Please remember that you can verify that your code adheres to the coding standards by running `composer coding-standards`.

### Tests
This project has been developed following the [TDD](https://en.wikipedia.org/wiki/Test-driven_development) principles, and it strives for maximum test coverage. Therefore, you are encouraged to write tests for your new code. If your code is a bug fix, please write a test that proves that your code actually fixes the bug.

If you don't know how to write tests, please don't be discouraged, and send your pull request without tests, I will try to add them myself later.

To run the test suite and the code coverage report, simply execute `composer test`.

### Static Analysis Tools
To ensure the quality of the codebase is of a high standard, the following static analysis tools are run as part of the CI pipeline:

| Tool | Notes | How to run |
| ---- | ----- | ---------- |
| [Scrutinizer](https://scrutinizer-ci.com/g/jmgq/php-a-star/) | Tracks how data flows through the application to detect security issues, bugs, unused code, and more | Online only |
| Phan | Runs on the lowest, most strict level | `composer static-analysis:phan` |
| PHPStan | Runs on the highest, most strict level | `composer static-analysis:phpstan` |
| Psalm | Runs on lowest, most strict level | `composer static-analysis:psalm` |

You can run all the local static analysis tools with `composer static-analysis`.

### Contributors
Feel free to add yourself to the list of [contributors](CONTRIBUTORS.md).

Changelog
---------
Read the [changelog](CHANGELOG.md).
