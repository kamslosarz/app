<?php /** @noinspection ALL */

namespace fixture\TestService;

use ServiceContainer\Service\Service;

class TestService extends Service
{
    private $testService;
    private $param1;
    private $param2;

    public function __construct($testService, $param1 = null, $param2 = null)
    {
        $this->testService = $testService;
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    public function __invoke(): string
    {
        return 'Test service invoked';
    }
}