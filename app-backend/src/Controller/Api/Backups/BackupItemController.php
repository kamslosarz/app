<?php

namespace App\Controller\Api\Backups;

use App\ORM\Repository\Backup\BackupRepository;
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
        $backup = $backupRepository->findOne($id);

        return $this->jsonItemResponse($backup->__toArray());
    }
}