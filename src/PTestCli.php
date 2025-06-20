<?php


namespace Phore\Tester;


use Phore\FileSystem\PhoreFile;
use Phore\Tester\Attr\TestAttribute;
use Phore\Tester\Ex\AssertionFailedException;

class PTestCli
{


    protected function runSingleTest($object, $method)
    {
        if ( ! $object instanceof \PHPUnit\Framework\TestCase)
            throw new \InvalidArgumentException("Object must be instance of PHPUnit\Framework\TestCase");


        try {
            $object->$method();
            $expectedException = $object->__getExpectedException();
            if ($expectedException !== null)
                throw new AssertionFailedException("Expected Exception", $expectedException, "<no exception>", "expectException");
        } catch(AssertionFailedException $e) {
            throw $e;
        } catch (\Exception|\Error $e) {
            $expectedException = $object->__getExpectedException();
            if ($expectedException === null)
                throw $e;
            if (isset($expectedException["class"]))
                if ( ! $e instanceof $expectedException["class"])
                    throw new AssertionFailedException("Expected Exception", $expectedException, $e, "expectException");
            if (isset($expectedException["message"]))
                if ( ! str_contains($e->getMessage(), $expectedException["message"]))
                    throw new AssertionFailedException("Expected Exception Message", $expectedException["message"], $e->getMessage(), "expectExceptionMessage");

        }
        $object->expectException(null); // Reset the expected exception
    }


    protected function runTestFromFile(PhoreFile $file)
    {
        $class = $file->getFilename();

        $classes = get_declared_classes();
        include $file;
        $diff = array_diff(get_declared_classes(), $classes);
        $class = reset($diff);
        phore_out("Testing $file '$class'...");

        $obj = new $class();
        $ref = new \ReflectionObject($obj);
        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $methodRef) {
            $mName = $methodRef->getName();
            if ( ! startsWith($mName, "test"))
                continue;

            try {

                if (method_exists($methodRef, "getAttributes")) {
                    $attributes = $methodRef->getAttributes();
                    if (count($attributes) > 0) {
                        foreach ($attributes as $attribute) {
                            $tester = $attribute->newInstance();
                            if (!$tester instanceof TestAttribute)
                                throw new \InvalidArgumentException("Invalid Attribute " . get_debug_type($tester) . " in $mName");
                            $tester->test($obj, $mName);
                        }

                        continue;
                    }
                }

                phore_out("+ $mName");


                $this->runSingleTest($obj, $mName);


                echo "\033[32m [OK]\033[0m";
            } catch (AssertionFailedException $e) {

                echo "\033[31m [FAIL]\033[0m";
                echo "\n\n" . $e->getMessage();
                phore_out("!!! Assertion failed in file {$e->getTrace()[0]["file"]} Line: {$e->getTrace()[0]["line"]}''\n");
                exit(2);
            } catch (\Exception $e) {
                echo "\033[31m [FAIL]\033[0m";

                phore_out("!!! Exception: " . $e->getMessage());
                if ($e instanceof \Stringable)
                    echo "\n" . $e;
                echo "\n\nThrown in: {$e->getFile()}:{$e->getLine()}";
                echo "\n" . $e->getTraceAsString();
                echo "\n";
                exit (1);
            } catch (\Error $e) {
                echo "\033[31m [FAIL]\033[0m";
                phore_out("!!! Error: " . $e->getMessage());
                if ($e instanceof \Stringable)
                    echo "\n" . $e;
                echo "\n\nThrown in: {$e->getFile()}:{$e->getLine()}";
                echo "\n" . $e->getTraceAsString();
                echo "\n";
                exit (1);
            }
        }
        echo "\n";
    }


    public function main($argv, $argc) {
        if ($argv[1] ?? null !== null) {
            $this->runTestFromFile(phore_file($argv[1]));
            phore_out("TESTS PASSED\n");
            return;
        }

        foreach (phore_dir(posix_getcwd() . "/test")->genWalk("*Test.php", true) as $curFile) {
            $this->runTestFromFile($curFile);
        }
        phore_out("ALL TESTS PASSED\n");

    }
}
