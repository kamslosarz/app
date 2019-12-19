<?php

namespace App\Controller\Api\Backups;

use App\Controller\AbstractController\AppController;
use App\ORM\Repository\Backup\BackupRepository;
use App\Response\ListResponse;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupsListController extends AppController
{
    /**
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function indexAction(): string
    {
        $repository = new BackupRepository();
        $collection = $repository->find();

        $listResponse = new ListResponse($collection->__toArray());

        return $listResponse->toJson();
    }
}