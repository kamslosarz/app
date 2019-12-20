<?php

use App\Controller\Api\Access\AccessController;
use App\Controller\Api\Backups\BackupItemAddController;
use App\Controller\Api\Backups\BackupItemController;
use App\Controller\Api\Backups\BackupItemDeleteController;
use App\Controller\Api\Backups\BackupItemUpdateController;
use App\Controller\Api\Backups\BackupsListController;
use App\Controller\Api\Navigation\NavigationListController;
use EventManager\Event\Context;

return [
    'get:/getNavigationsList' => [
        [AccessController::class, 'indexAction'],
        [NavigationListController::class, 'listAction'],
    ],
    'get:/getBackupsList' => [
        [AccessController::class, 'indexAction'],
        [BackupsListController::class, 'listAction'],
    ],
    'put:/backup' => [
        [AccessController::class, 'indexAction'],
        [BackupItemAddController::class, 'addAction'],
    ],
    'post:/backup' => [
        [AccessController::class, 'indexAction'],
        [BackupItemUpdateController::class, 'updateAction'],
    ],
    'get:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'itemAction'],
    ],
    'delete:/backup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemDeleteController::class, 'deleteAction'],
    ],
    '*' => [
        function (Context $context) {
            $context->set('responseCode', 400);
        },
    ],
];