<?php

use App\Controller\ContactController;
use App\Controller\IndexController;
use App\Controller\LoginController;
use EventManager\Event\Context;

return [
    '/' => [
        [
            IndexController::class,
            'indexAction'
        ],
        function (Context $context) {
            $context->__toArray();

            return 'hello world from callback';
        }
    ],
    '/login' => [
        [
            LoginController::class,
            'loginAction'
        ]
    ],
    'post:/login' => [
        [
            LoginController::class,
            'loginPostAction'
        ]
    ],
    '/contact' => [
        [
            ContactController::class,
            'indexAction'
        ]
    ],
    'post:/contact' => [
        [
            ContactController::class,
            'indexPostAction'
        ]
    ],
    '*' => [
        function () {
            return 'route that not exists';
        }
    ]
];