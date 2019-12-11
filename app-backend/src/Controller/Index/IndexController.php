<?php

namespace App\Controller\Index;

use App\Controller\AbstractController\AppController;
use View\ViewException;

class IndexController extends AppController
{
    /**
     * @return string
     * @throws ViewException
     */
    public function indexAction()
    {
        return $this->getView()->render('index/index.phtml', [
            'message' => ''
        ]);
    }
}