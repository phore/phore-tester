<?php


namespace Phore\Tester\Attr;


interface TestAttribute
{

    public function test(object $testObject, string $methodName);
}