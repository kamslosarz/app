<?php

namespace App\Controller\Api\Backups;

use App\ORM\Repository\Backup\BackupRepository;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupsListController extends BackupController
{
    /**
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function listAction(): string
    {
        $repository = new BackupRepository();
        $items = $repository->find(10);
        $offset = 0;

        return $this->jsonListResponse($items->__toArray(), $items->count(), $offset, $repository->count(), 10);
    }
}