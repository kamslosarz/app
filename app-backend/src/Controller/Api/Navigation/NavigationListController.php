<?php

namespace App\Controller\Api\Navigation;

use App\Controller\AbstractController\AppController;
use App\Response\ListResponse;

class NavigationListController extends AppController
{
    public function listAction(): string
    {
        $listResponse = new ListResponse([
            [
                'id' => 1,
                'title' => 'Strona głowna',
                'href' => '/index'
            ],
            [
                'id' => 2,
                'title' => 'Lista',
                'href' => '/list'
            ],
            [
                'id' => 3,
                'title' => 'Dodaj',
                'href' => '/add'
            ]
        ], 10, 3, 0, 3);

        return $listResponse->toJson();
    }
}