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

        if (is_array($expected) || is_array($actual) || ( (is_string($actual) || is_string($expected)) && (strlen($expected) > 2000 || strlen($actual) > 2000) )) {
            file_put_contents("/tmp/expected.out", print_r($expected, true));
            file_put_contents("/tmp/actual.out", print_r($actual, true));
            $msg = "Expected (/tmp/expeced.out) not equals acutal (/tmp/actual.out).";
        } else if (is_string($expected) && is_string($actual) && strlen($expected) > 25 && strlen($actual) > 25 || strpos($expected, "\n") !== false || strpos($actual, "\n") !== false ) {
            $msg = "<<<<<<<<< EXPECTED <<<<<<<<<\n";
            $msg .= $expected . "\n";
            $msg .= ">>>>>>>> ACTUAL   >>>>>>>>>\n";
            $msg .= $actual . "\n";
            $msg .= "===========================";
        } else {
            $msg = "Expected '$expected' != '$actual'";
        }

        parent::__construct( "$msg", $code, $previous);
    }


}
