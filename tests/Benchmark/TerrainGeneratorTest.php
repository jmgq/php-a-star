<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\TerrainGenerator;

class TerrainGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    public function invalidNaturalNumberProvider()
    {
        return array(
            array(0),
            array(-1),
            array(2.5),
            array(null),
            array(array()),
            array('foo'),
        );
    }

    public function invalidOptionalIntegerProvider()
    {
        return array(
            array('a'),
            array(array()),
            array(false),
            array(1.5),
            array(-1.5),
            array(''),
            array(' '),
        );
    }

    protected function setUp()
    {
        $this->sut = new TerrainGenerator();
    }

    public function testShouldGenerateTerrain()
    {
        $rows = 3;
        $columns = 5;

        $result = $this->sut->generate($rows, $columns);

        $this->assertSame($rows, $result->getTotalRows());
        $this->assertSame($columns, $result->getTotalColumns());
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotGenerateWithInvalidRows($invalidRows)
    {
        $columns = 5;

        $this->sut->generate($invalidRows, $columns);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotGenerateWithInvalidColumns($invalidColumns)
    {
        $rows = 3;

        $this->sut->generate($rows, $invalidColumns);
    }

    /**
     * @dataProvider invalidOptionalIntegerProvider
     * @expectedException \InvalidArgumentException
     */
    public function testShouldNotGenerateWithInvalidSeed($invalidSeed)
    {
        $rows = 3;
        $columns = 5;

        $this->sut->generate($rows, $columns, $invalidSeed);
    }
}
