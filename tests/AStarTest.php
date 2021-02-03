<?php

namespace JMGQ\AStar\Tests;

class AStarTest extends BaseAStarTest
{
    protected function setUp()
    {
        $this->sut = $this->getMockForAbstractClass('JMGQ\AStar\AStar');
    }
}
