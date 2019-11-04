<?php


namespace unit\TestCase;


use Container\Process\ProcessContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Tests\TestCase\FunctionalTestCase;

class FunctionalTestCaseTest extends TestCase
{
    public function testShouldConstructFunctionalTestCase()
    {
        $functionalTestCase = Mockery::mock(FunctionalTestCase::class)
            ->makePartial();

        $this->assertInstanceOf(FunctionalTestCase::class, $functionalTestCase);
    }

    public function testShouldSetGetRequest()
    {
        /** @var FunctionalTestCase $functionalTestCase */
        $functionalTestCase = Mockery::mock(FunctionalTestCase::class)
            ->makePartial();

        $functionalTestCase->setGetRequest('index.php?parameter=1&parameter2[]=asdasdsa');
        $this->assertEquals(['parameter' => 1, 'parameter2' => ['asdasdsa']], $_GET);
    }

    public function testShouldSetPostRequest()
    {
        /** @var FunctionalTestCase $functionalTestCase */
        $functionalTestCase = Mockery::mock(FunctionalTestCase::class)
            ->makePartial();

        $postData = [
            'param1' => 'value1',
            'param2' => 'value2',
        ];
        $functionalTestCase->setPostRequest('index .php?param=value', $postData);
        $this->assertEquals($postData, $_POST);
        $this->assertEquals(['param' => 'value'], $_GET);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldInvokeApp()
    {
        /** @var FunctionalTestCase $functionalTestCase */
        $functionalTestCase = Mockery::mock(FunctionalTestCase::class)
            ->makePartial();

        $functionalTestCase->invokeApp();

        $reflection = new ReflectionClass($functionalTestCase);
        $processContextProperty = $reflection->getProperty('processContext');
        $processContextProperty->setAccessible(true);

        $this->assertInstanceOf(ProcessContext::class, $processContextProperty->getValue($functionalTestCase));
    }
}