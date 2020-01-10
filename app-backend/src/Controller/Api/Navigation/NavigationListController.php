<?php

namespace App\Controller\Api\Navigation;

use App\Controller\Api\ApiController;

class NavigationListController extends ApiController
{
    public function listAction(): string
    {
        $list = [
            [
                'id' => 1,
                'title' => 'Strona głowna',
                'href' => '/'
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
        ];

        return $this->jsonListResponse($list, 3, 0,3);
    }
}