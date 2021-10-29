<?php


namespace Phore\Tester\Ex;


use Phore\Tester\Format\PrintableFormatHelper;
use Throwable;

class AssertionFailedException extends \Exception
{

    protected $expected;
    protected $actual;

    public function __construct($message = "", $expected, $actual, string $assertion, $code = 0, Throwable $previous = null)
    {
        $this->expected = $expected;
        $this->actual = $actual;
        
        $expected = PrintableFormatHelper::GetPrintableType($expected);
        $actual = PrintableFormatHelper::GetPrintableType($actual);

        if (strlen($expected) > 2000 || strlen($expected) > 2000) {
            file_put_contents("/tmp/expected.out", print_r($expected, true));
            file_put_contents("/tmp/actual.out", print_r($actual, true));
            $msg = "Assertion failed: $assertion([expected see /tmp/expeced.out] != [actual see /tmp/actual.out]).";
        } else if (is_string($expected) && is_string($actual) && strlen($expected) > 60 && strlen($actual) > 60 || strpos($expected, "\n") !== false || strpos($actual, "\n") !== false ) {
            $msg = "Assertion failed: $assertion(\n<<<<<<<<< EXPECTED <<<<<<<<<\n";
            $msg .= $expected . "\n";
            $msg .= ">>>>>>>> ACTUAL   >>>>>>>>>\n";
            $msg .= $actual . "\n";
            $msg .= "===========================\n);";
        } else {
            $msg = "Assertion failed: $assertion( $expected != $actual )";
        }

        parent::__construct( "$msg", $code, $previous);
    }


}
