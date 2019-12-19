<?php

namespace App\Controller\Api\Navigation;

use App\Controller\AbstractController\AppController;
use App\Response\ListResponse;

class NavigationListController extends AppController
{
    public function indexAction(): string
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
        ]);

        return $listResponse->toJson();
    }
}