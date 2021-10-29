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

}