<?php


namespace Phore\Tester;


class PTestCli
{


    public static function main($argv, $argc) {


        foreach (phore_dir(posix_getcwd() . "/test")->genWalk("*Test.php") as $curFile) {
            $class = $curFile->getFilename();




            $classes = get_declared_classes();
            include $curFile;
            $diff = array_diff(get_declared_classes(), $classes);
            $class = reset($diff);
            phore_out("Testing $curFile '$class'...");

            $obj = new $class();
            $ref = new \ReflectionObject($obj);
            foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $methodRef) {
                $mName = $methodRef->getName();
                if ( ! startsWith($mName, "test"))
                    continue;
                phore_out("+ $mName");
                try {
                    $obj->$mName();
                } catch (\Exception $e) {
                    phore_out("!!! Error: " . $e->getMessage());
                    echo "\n";
                    exit (1);
                }
                phore_out("PASS");

            }

        }


    }
}