<?php

namespace App\Controller\Index;

use App\Controller\AbstractController\AppController;

class IndexController extends AppController
{
    /**
     * @return string
     */
    public function indexAction()
    {
        return $this->getView()->render('index/index.phtml', [
            'message' => ''
        ]);
    }
}