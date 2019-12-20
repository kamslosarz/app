<?php

namespace App\Controller\Api\Backups;

use App\ORM\Repository\Backup\BackupRepository;
use App\Response\ListResponse;
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
        $items = $repository->find();
        $offset = 0;

        $listResponse = new ListResponse($items->__toArray(), 100, $items->count(), $offset, $repository->count());

        return $listResponse->toJson();
    }
}