<?php

namespace JMGQ\AStar\Tests\Benchmark;

use JMGQ\AStar\Benchmark\InputValidator;

class InputValidatorTest extends \PHPUnit_Framework_TestCase
{
    private $sut;
    private $input;
    private $output;

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
        $this->input = $this->getMock('Symfony\Component\Console\Input\InputInterface');

        $this->output = $this->getMock('Symfony\Component\Console\Style\StyleInterface');

        $this->sut = new InputValidator($this->output);
    }

    public function testShouldValidateCorrectValues()
    {
        $validSizes = array('5', '10');
        $validIterations = '15';
        $validSeed = '123456';

        $this->setInputExpectations($validSizes, $validIterations, $validSeed);

        $this->output->expects($this->never())
            ->method($this->anything());

        $result = $this->sut->validate($this->input);

        $this->assertTrue($result);
    }

    public function testShouldNotValidateIncorrectValues()
    {
        $invalidSizes = array('a');
        $invalidIterations = 'b';
        $invalidSeed = 'c';

        $this->setInputExpectations($invalidSizes, $invalidIterations, $invalidSeed);

        $this->output->expects($this->exactly(3))
            ->method('error')
            ->withConsecutive(
                array('The size must be an integer greater than 0'),
                array('The number of iterations must be an integer greater than 0'),
                array('The seed must be an integer')
            );

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotValidateIncorrectSizes($invalidSize)
    {
        $invalidSizes = array($invalidSize, '10', $invalidSize);
        $validIterations = '15';
        $validSeed = '123456';

        $this->setInputExpectations($invalidSizes, $validIterations, $validSeed);

        $this->output->expects($this->exactly(2))
            ->method('error')
            ->with('The size must be an integer greater than 0');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidNaturalNumberProvider
     */
    public function testShouldNotValidateIncorrectIterations($invalidIterations)
    {
        $validSizes = array('8');
        $validSeed = '123456';

        $this->setInputExpectations($validSizes, $invalidIterations, $validSeed);

        $this->output->expects($this->once())
            ->method('error')
            ->with('The number of iterations must be an integer greater than 0');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider invalidOptionalIntegerProvider
     */
    public function testShouldNotValidateIncorrectSeed($invalidSeed)
    {
        $validSizes = array('8');
        $validIterations = '15';

        $this->setInputExpectations($validSizes, $validIterations, $invalidSeed);

        $this->output->expects($this->once())
            ->method('error')
            ->with('The seed must be an integer');

        $result = $this->sut->validate($this->input);

        $this->assertFalse($result);
    }

    public function testShouldValidateOptionalSeed()
    {
        $validSizes = array('8');
        $validIterations = '15';
        $validOptionalSeed = null;

        $this->setInputExpectations($validSizes, $validIterations, $validOptionalSeed);

        $this->output->expects($this->never())
            ->method($this->anything());

        $result = $this->sut->validate($this->input);

        $this->assertTrue($result);
    }

    private function setInputExpectations(array $sizes, $iterations, $seed)
    {
        $this->input->expects($this->exactly(3))
            ->method('getOption')
            ->willReturnMap(array(
                array('size', $sizes),
                array('iterations', $iterations),
                array('seed', $seed),
            ));
    }
}
