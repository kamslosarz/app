<?php

use App\Controller\Contact\ContactController;
use App\Controller\Index\IndexController;
use App\Controller\Login\LoginController;
use EventManager\Event\Context;

return [
    '/' => [
        [
            IndexController::class,
            'indexAction'
        ],
        function (Context $context) {
            $context->__toArray();

            return '';
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