<?php

use App\Controller\Api\Access\AccessController;
use App\Controller\Api\Backups\BackupAddController;
use App\Controller\Api\Backups\BackupItemController;
use App\Controller\Api\Backups\BackupItemDeleteController;
use App\Controller\Api\Backups\BackupsListController;
use App\Controller\Api\Navigation\NavigationListController;
use EventManager\Event\Context;

return [
    'get:/getNavigationsList' => [
        [AccessController::class, 'indexAction'],
        [NavigationListController::class, 'indexAction'],
    ],
    'get:/getBackupsList' => [
        [AccessController::class, 'indexAction'],
        [BackupsListController::class, 'indexAction'],
    ],
    'put:/backup' => [
        [AccessController::class, 'indexAction'],
        [BackupAddController::class, 'indexAction'],
    ],
    'get:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'indexAction'],
    ],
    'delete:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemDeleteController::class, 'indexAction'],
    ],
    '*' => [
        function (Context $context) {
            $context->set('responseCode', 400);
        },
    ],
];