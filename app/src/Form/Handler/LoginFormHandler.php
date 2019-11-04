<?php

namespace App\Form\Handler;

use Form\Handler\FormHandler;

class LoginFormHandler extends FormHandler
{
    public function validate(array $data): void
    {
        if(!$data['login'])
        {
            $this->addError('login', 'Invalid login');
        }
        if(!$data['password'])
        {
            $this->addError('password', 'Invalid password');
        }
    }

    public function handle(array $data): void
    {



    }
}