<?php

namespace App\Controller\Api\Backups;

use App\ORM\Repository\Backup\BackupRepository;
use App\Response\JsonResponse;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupItemController extends BackupController
{
    /**
     * @param $id
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function itemAction($id): string
    {
        $backupRepository = new BackupRepository();
        $backupItem = $backupRepository->findOne($id);

        $jsonResponse = new JsonResponse(['item' => $backupItem->__toArray()]);

        return $jsonResponse->toJson();
    }
}