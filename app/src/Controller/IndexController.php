<?php

namespace App\Controller;

use View\ViewException;

class IndexController extends Controller
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