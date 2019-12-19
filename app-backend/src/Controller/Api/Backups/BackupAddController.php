<?php

namespace App\Controller\Api\Backups;

use App\Controller\AbstractController\AppController;
use App\ORM\Model\Backup\BackupItem;
use App\Response\JsonResponse;
use Factory\FactoryException;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\OrmException;
use Validator\Constraint\CharacterLengthConstraint;
use Validator\Constraint\DateTimeConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;

class BackupAddController extends AppController
{
    /**
     * @return string
     * @throws DataBaseAdapterException
     * @throws FactoryException
     * @throws OrmException
     */
    public function indexAction(): string
    {
        $requestData = $this->getRequest()->getInput()->__toArray();

        $constraintBuilder = new ConstraintBuilder();
        $constraintBuilder->addConstraint('name', CharacterLengthConstraint::class, [
            'max' => 255,
            'min' => 1,
        ])->addConstraint('description', CharacterLengthConstraint::class, [
            'max' => 255,
            'min' => 1,
        ])->addConstraint('date', DateTimeConstraint::class, [
            'format' => 'Y-m-d',
        ]);

        if (!$this->validate($requestData, $constraintBuilder)) {
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