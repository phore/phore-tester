<?php


namespace PHPUnit\Framework;


use Phore\Tester\Ex\AssertionFailedException;

class TestCase
{

    /**
     * @param $expected
     * @param $actual
     * @return bool
     * @throws AssertionFailedException
     */
    public function assertEquals($expected, $actual)
    {
        if ($expected !== $actual)
            throw new AssertionFailedException("Equals", $expected, $actual, "assertEquals");
        return true;
    }


    public function assertNotEmpty($actual)
    {
        if (empty($actual))
            throw new AssertionFailedException("NotEmpty", "not empty", "empty", "assertNotEmpty");
        return true;
    }

    public function assertInstanceOf($expected, $actual)
    {
        if ( ! $actual instanceof $expected)
            throw new AssertionFailedException("InstanceOf", $expected, $actual, "assertInstanceOf");
        return true;
    }

    private $expectedException = null;

    public function expectException($exceptionClass)
    {
        $this->expectedException = $exceptionClass;
    }

    public function __getExpectedException()
    {
        return $this->expectedException;
    }


}
