<?php

namespace App\Service\TokenGenerator;

use ServiceContainer\Service\Service;

class TokenGenerator extends Service
{
    public function generateToken(): string
    {
        return md5(uniqid('token_'));
    }
}