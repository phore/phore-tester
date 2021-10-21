<?php


namespace Phore\Tester\Attr;


interface TestAttribute
{

    public function test($testObject, $methodName);
}