# phore-tester
Lightweight unit testing using phpunit like api




## Repeat Tests

```php
 #[ApplyFixture(path: __DIR__ . "/cases")]
public function testSchemaCases($test)
{
    $schemaIn = require($test. ".in.php");
    $compareFile = file_get_contents($test. ".exp.sql");
}
```