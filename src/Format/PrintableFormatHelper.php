<?php

namespace Phore\Tester\Format;

class PrintableFormatHelper
{
    public static function GetPrintableType($input) : string {
        if (is_string($input)) return "'" . $input ."'";
        if ($input === true) return "true";
        if ($input === false) return "false";
        if ($input === null) return "NULL";
        if (is_int($input)) return (string)$input;
        if (is_float($input)) return (string)$input;
        if (is_array($input) || is_object($input)) {
            $ret = print_r ($input, true);
            if (strlen($ret) < 255) {
                return preg_replace("/\n[ ]*/m", " ", $ret);
            } else {
                return "\n" . print_r ($input, true). "";
            }
        }
        return get_debug_type($input);
    }
}