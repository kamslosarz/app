<?php

namespace App\Controller\Api\Backups;

use App\Response\JsonResponse;

class BackupItemController
{
    public function indexAction($id): string
    {
        $jsonResponse = new JsonResponse(['item' => [
            'id' => $id,
            'name' => 'backup name',
            'date' => date('m-d-y H:i:s'),
            'description' => 'some desc',
        ]]);

        return $jsonResponse->toJson();
    }
}