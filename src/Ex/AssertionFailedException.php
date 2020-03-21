<?php


namespace Phore\Tester\Ex;


use Throwable;

class AssertionFailedException extends \Exception
{

    protected $expected;
    protected $actual;

    public function __construct($message = "", $expected, $actual, $code = 0, Throwable $previous = null)
    {
        $this->expected = $expected;
        $this->actual = $actual;
        parent::__construct($message, $code, $previous);
    }


}