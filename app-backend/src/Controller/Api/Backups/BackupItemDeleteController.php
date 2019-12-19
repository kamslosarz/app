<?php

namespace App\Controller\Api\Backups;

use App\Controller\AbstractController\AppController;
use App\ORM\Model\Backup\BackupItem;
use App\ORM\Repository\Backup\BackupRepository;
use App\Response\JsonResponse;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupItemDeleteController extends AppController
{
    /**
     * @param int $id
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function indexAction(int $id)
    {
        $backupRepository = new BackupRepository();
        /** @var BackupItem $backupItem */
        $backupItem = $backupRepository->findOne($id);
        $backupItem->delete();

        return (new JsonResponse(['ok']))->toJson();
    }
}