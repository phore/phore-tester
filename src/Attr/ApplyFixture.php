<?php
namespace Phore\Tester\Attr;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ApplyFixture implements TestAttribute
{
    public function __construct(
        public string $path
    )
    {}


    public function test(object $object, string $methodName)
    {
        $filesIn 
        phore_out("")
    }
    
}