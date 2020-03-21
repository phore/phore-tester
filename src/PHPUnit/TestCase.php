<?php


namespace PHPUnit\Framework;


use Phore\Tester\Ex\AssertionFailedException;

class TestCase
{

    protected function assertEquals($expected, $actual) {
        if ($expected !== $actual)
            throw new AssertionFailedException("Equals", $expected, $actual);
        return true;
    }

}