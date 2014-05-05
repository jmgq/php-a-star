<?php

namespace JMGQ\AStar\Tests;

class AStarTest extends BaseAStarTest
{
    public function setUp()
    {
        $this->sut = $this->getMockForAbstractClass('JMGQ\AStar\AStar');
    }
}
