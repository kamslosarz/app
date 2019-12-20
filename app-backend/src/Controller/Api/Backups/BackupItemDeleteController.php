<?php

namespace App\Controller\Api\Backups;

use App\ORM\Model\Backup\BackupItem;
use App\ORM\Repository\Backup\BackupRepository;
use App\Response\JsonResponse;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupItemDeleteController extends BackupController
{
    /**
     * @param int $id
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function deleteAction(int $id)
    {
        $backupRepository = new BackupRepository();
        /** @var BackupItem $backupItem */
        $backupItem = $backupRepository->findOne($id);
        $backupItem->delete();

        return (new JsonResponse(['ok']))->toJson();
    }
}