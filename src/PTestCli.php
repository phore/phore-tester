<?php


namespace Phore\Tester;


use Phore\FileSystem\PhoreFile;
use Phore\Tester\Attr\TestAttribute;

class PTestCli
{
    

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
                    if (count ($attributes) > 0) {
                        foreach ($attributes as $attribute) {
                            $tester = $attribute->newInstance();
                            if ( ! $tester instanceof TestAttribute)
                                throw new \InvalidArgumentException("Invalid Attribute " . get_debug_type($tester). " in $mName");
                            $tester->test($obj, $mName);
                        }
                        echo (" [OK]\n");
                        continue;
                    }
                }

                phore_out("+ $mName");
                $obj->$mName();
                echo ("[OK]\n");
            } catch (\Exception $e) {
                phore_out("!!! Exception: " . $e->getMessage());
                echo "\n\nThrown in: {$e->getFile()}:{$e->getLine()}";
                echo "\n" . $e->getTraceAsString();
                echo "\n";
                exit (1);
            } catch (\Error $e) {
                phore_out("!!! Error: " . $e->getMessage());
                echo "\n\nThrown in: {$e->getFile()}:{$e->getLine()}";
                echo "\n" . $e->getTraceAsString();
                echo "\n";
                exit (1);
            }

        }

    }

    
    public function main($argv, $argc) {
        if ($argv[1] !== null) {
            $this->runTestFromFile(phore_file($argv[1]));
            return;
        }

        foreach (phore_dir(posix_getcwd() . "/test")->genWalk("*Test.php", true) as $curFile) {
            $this->runTestFromFile($curFile);
        }
        phore_out("ALL TESTS PASSED\n");

    }
}
