<?php

use fixture\TestService\TestService;

return [
    'testService' => [
        TestService::class, [
            'testService',
            'param1',
            'param2',
        ]
    ],
    'testServiceWthDependency' => [
        TestService::class, [
            '@testService',
            'param1',
            'param2',
        ]
    ]
];