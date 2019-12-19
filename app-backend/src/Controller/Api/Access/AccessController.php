<?php

namespace App\Controller\Api\Access;

use App\Controller\AbstractController\AppController;

class AccessController extends AppController
{
    public function indexAction(): void
    {
        $this->context->set('jsonResponse', true);
    }
}