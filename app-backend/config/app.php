<?php

use App\Factory\EventDispatcherFactory;
use App\Factory\EventFactory;
use App\Factory\JsonErrorResponseFactory;
use App\Factory\RequestFactory;
use App\Factory\ResponseFactory;
use App\Factory\RouterFactory;
use App\Factory\ViewFactory;
use Collection\Collection;

return [
    'appProcessContext' => new Collection([
        'request' => [RequestFactory::class, []],
        'router' => [RouterFactory::class, (include __DIR__ . '/routes.php') + (include __DIR__ . '/api/routes.php')],
        'view' => [
            ViewFactory::class, [
                'resources' => [
                    APP_DIR . '/resources',
                    APP_DIR . '/vendor/framework/form/resources'
                ],
                'viewExtensions' => include __DIR__ . '/view-extensions.php'
            ]
        ],
        'event' => [EventFactory::class, ['servicesMap' => include __DIR__ . '/services.php']],
        'eventDispatcher' => [EventDispatcherFactory::class, []],
        'response' => [ResponseFactory::class, []]
    ]),
    'errorProcessContext' => new Collection([
        'response' => [JsonErrorResponseFactory::class, []]
    ])
];
