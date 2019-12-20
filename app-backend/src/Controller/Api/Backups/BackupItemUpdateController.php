<?php

namespace App\Controller\Api\Backups;

use App\ORM\Model\Backup\BackupItem;
use App\ORM\Repository\Backup\BackupRepository;
use App\Response\JsonResponse;
use Factory\FactoryException;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;
use Validator\Constraint\NumberConstraint;

class BackupItemUpdateController extends BackupController
{
    /**
     * @param int $id
     * @return string
     * @throws DataBaseAdapterException
     * @throws FactoryException
     * @throws OrmException
     */
    public function updateAction(): string
    {
        $requestData = $this->getRequest()->getInput()->__toArray();
        $constraintBuilder = $this->getConstraintBuilder();
        $constraintBuilder->addConstraint('id', NumberConstraint::class, ['min' => 1]);
        if(!$this->validate($requestData, $this->getConstraintBuilder()))
        {
            return (new JsonResponse(['errors' => $this->getErrors()]))->toJson();
        }

        $backupRepository = new BackupRepository();
        /** @var BackupItem $backupItem */

        $id = $requestData['id'];
        $backupItem = $backupRepository->findOne($id);
        $backupItem->setDate($requestData['date']);
        $backupItem->setName($requestData['name']);
        $backupItem->setDescription($requestData['description']);
        $backupItem->save();

        $jsonResponse = new JsonResponse(['item' => $backupItem->__toArray()]);

        return $jsonResponse->toJson();
    }
}