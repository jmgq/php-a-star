<?php

namespace JMGQ\AStar\Tests;

use JMGQ\AStar\AStar;

class AStarTest extends BaseAStarTest
{
    protected function setUp(): void
    {
        $this->sut = $this->getMockForAbstractClass(AStar::class);
    }
}
