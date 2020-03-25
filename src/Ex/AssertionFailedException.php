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

        if (is_array($expected) || is_array($actual) || ( (is_string($actual) || is_string($expected)) && (strlen($expected) > 50 || strlen($actual) > 50) )) {
            file_put_contents("/tmp/expected.out", print_r ($expected));
            file_put_contents("/tmp/actual.out", print_r($actual));
            $msg = "Expected (/tmp/expeced.out) not equals acutal (/tmp/actual.out).";

        } else {
            $msg = "Expected '$expected' != '$actual'";
        }

        parent::__construct($message . " $msg", $code, $previous);
    }


}
