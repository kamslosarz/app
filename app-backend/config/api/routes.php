<?php

use App\Controller\Api\Access\AccessController;
use App\Controller\Api\Backups\BackupItemController;
use App\Controller\Api\Backups\BackupsListController;
use App\Controller\Api\Navigation\NavigationListController;
use EventManager\Event\Context;

return [
    'get:/auth/token' => [
        [AccessController::class, 'generateTokenAction'],
    ],

    'get:/navigations' => [
        [AccessController::class, 'indexAction'],
        [NavigationListController::class, 'listAction'],
    ],


    'get:/backups/{offset}' => [
        [AccessController::class, 'indexAction'],
        [BackupsListController::class, 'listAction'],
    ],
    'post:/backups/search/{offset}' => [
        [AccessController::class, 'indexAction'],
        [BackupsListController::class, 'searchAction'],
    ],
    'put:/backup' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'addAction'],
    ],
    'post:/backup' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'updateAction'],
    ],
    'get:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'itemAction'],
    ],
    'delete:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'deleteAction'],
    ],

    '*' => [
        function (Context $context) {
            $context->set('responseCode', 400);
        },
    ],
];