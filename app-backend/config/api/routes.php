<?php

use App\Controller\Api\Access\AccessController;
use App\Controller\Api\Backups\BackupAddController;
use App\Controller\Api\Backups\BackupItemController;
use App\Controller\Api\Backups\BackupsListController;
use App\Controller\Api\Navigation\NavigationListController;
use EventManager\Event\Context;

return [
    'get:/getNavigation' => [
        [AccessController::class, 'indexAction'],
        [NavigationListController::class, 'indexAction'],
    ],
    'get:/getBackupList' => [
        [AccessController::class, 'indexAction'],
        [BackupsListController::class, 'indexAction'],
    ],
    'put:/addBackup' => [
        [AccessController::class, 'indexAction'],
        [BackupAddController::class, 'indexAction'],
    ],
    'get:/getBackup/{id}' => [
        [AccessController::class, 'indexAction'],
        [BackupItemController::class, 'indexAction'],
    ],
    '*' => [
        function (Context $context) {
            $context->set('responseCode', 400);
        },
    ],
];