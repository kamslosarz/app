<?php

namespace App\Controller\Api\Backups;

use App\ORM\Model\Backup\BackupItem;
use App\Response\JsonResponse;
use Factory\FactoryException;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;

class BackupItemAddController extends BackupController
{
    /**
     * @return string
     * @throws DataBaseAdapterException
     * @throws FactoryException
     * @throws OrmException
     */
    public function addAction(): string
    {
        $requestData = $this->getRequest()->getInput()->__toArray();
        if(!$this->validate($requestData, $this->getConstraintBuilder()))
        {
            return (new JsonResponse(['errors' => $this->getErrors()]))->toJson();
        }

        $backup = new BackupItem();
        $backup->setName($requestData['name']);
        $backup->setDescription($requestData['description']);
        $backup->setDate($requestData['date']);
        $backup->save();

        $jsonResponse = new JsonResponse(['item' => $backup->__toArray()]);

        return $jsonResponse->toJson();
    }
}