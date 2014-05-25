A Star algorithm for PHP
========================
[![Latest Stable Version](https://poser.pugx.org/jmgq/a-star/v/stable.svg)](https://packagist.org/packages/jmgq/a-star)
[![Build Status](https://travis-ci.org/jmgq/php-a-star.svg)](https://travis-ci.org/jmgq/php-a-star)
[![Coverage Status](https://coveralls.io/repos/jmgq/php-a-star/badge.png)](https://coveralls.io/r/jmgq/php-a-star)
[![License](https://poser.pugx.org/jmgq/a-star/license.svg)](https://packagist.org/packages/jmgq/a-star)

A Star pathfinding algorithm implementation for PHP.

Installation
------------
1. Install [composer](http://getcomposer.org/).

2. Add the A Star algorithm package to your `composer.json` file:
    ```
    "require": {
        ...
        "jmgq/a-star": "dev-master"
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

Example
-------
There is a working implementation in the `example` folder. In order to execute it, run the following command:
```sh
php example/example.php
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