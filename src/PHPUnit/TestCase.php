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
    
    public function assertStringStartsWith(string $expected, $actual)
    {
        if ( ! is_string($actual))
            throw new AssertionFailedException("StringStartsWith", $expected, phore_debug_type($actual), "assertStringStartsWith");
            
        if (substr($actual, 0, strlen($expected)) !== $expected)
            throw new AssertionFailedException("StringStartsWith", $expected, $actual, "assertStringStartsWith");
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
        if ( ! is_array($this->expectedException))
            $this->expectedException = [];
        $this->expectedException["class"] = $exceptionClass;
    }

    public function expectExceptionMessage($message)
    {
        if ( ! is_array($this->expectedException))
            $this->expectedException = [];
        $this->expectedException["message"] = $message;
    }

    public function expectExceptionMessageRegExp($message)
    {
        if ( ! is_array($this->expectedException))
            $this->expectedException = [];
        $this->expectedException["message"] = $message;
        $this->expectedException["regexp"] = true;
    }



    public function __getExpectedException()
    {
        return $this->expectedException;
    }


}
