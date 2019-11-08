<?php

use App\App;
use App\Factory\ErrorResponseFactory;
use App\Factory\EventDispatcherFactory;
use App\Factory\EventFactory;
use App\Factory\RequestFactory;
use App\Factory\ResponseFactory;
use App\Factory\RouterFactory;
use App\Factory\ViewFactory;
use Collection\Collection;
use Container\Process\ProcessContext;
use fixture\BrokenProcess\BrokenProcess;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testShouldInvokeApp()
    {
        $dependencyTree = [
            'appProcessContext' => new Collection([
                'request' => [RequestFactory::class, []],
                'router' => [RouterFactory::class, include FIXTURE_DIR . '/config/routes.php'],
                'event' => [EventFactory::class, ['servicesMap' => []]],
                'eventDispatcher' => [EventDispatcherFactory::class, []],
                'view' => [
                    ViewFactory::class, [
                        'resources' => [
                            APP_DIR . '/resources',
                            APP_DIR . '/vendor/framework/form/resources'
                        ]
                    ]
                ],
                'response' => [ResponseFactory::class, []]
            ]),
            'errorProcessContext' => new Collection()
        ];
        $app = new App();
        ob_start();
        $app($dependencyTree);
        $results = ob_get_clean();

        /** @var ProcessContext $processContext */
        $processContext = $app->getProcessContext();
        $this->assertInstanceOf(ProcessContext::class, $processContext);
        $this->assertNull($processContext->get('containerException'));
        $this->assertNull($processContext->get('applicationError'));
        $this->assertEquals('hello world', $results);
    }

    public function testShouldInvokeErrorProcessContext()
    {
        $dependencyTree = [
            'appProcessContext' => new Collection([
                'someProcess' => [
                    BrokenProcess::class,
                    []
                ]
            ]),
            'errorProcessContext' => new Collection([
                'request' => [RequestFactory::class, []],
                'view' => [
                    ViewFactory::class,
                    [
                        'resources' => [
                            APP_DIR . '/resources',
                            APP_DIR . '/vendor/framework/form/resources'
                        ]
                    ]
                ],
                'response' => [ErrorResponseFactory::class, []]
            ])
        ];
        $app = new App();
        ob_start();
        $app($dependencyTree);
        $results = ob_get_clean();
        /** @var ProcessContext $processContext */
        $processContext = $app->getProcessContext();
        if($processContext->has('applicationError'))
        {
            /** @var Exception $containerException */
            $applicationError = $processContext->get('applicationError');;
            $this->fail(sprintf('%s %s', $applicationError->getMessage(), $applicationError->getTraceAsString()));
        }
        if($processContext->has('containerException'))
        {
            /** @var Exception $containerException */
            $containerException = $processContext->get('containerException');
            $this->fail(sprintf('%s %s', $containerException->getMessage(), $containerException->getTraceAsString()));
        }

        $this->assertEquals('ERROR OCCURRED: some error', $results);
    }
}