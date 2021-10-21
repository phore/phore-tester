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
        $filesIn = glob($this->path . "/*");
        $tests = [];
        foreach ($filesIn as $fileIn) {
            if (preg_match("/([a-zA-Z0-9\/_\-]+)\.[a-zA-Z0-9\.]+$/", $fileIn, $matches))
                $tests[$matches[1]] = true;
        }
        foreach ($tests as $test => $val) {
            phore_out(" + $methodName('$test')");
            $object->$methodName($test);
        }

    }
    
}