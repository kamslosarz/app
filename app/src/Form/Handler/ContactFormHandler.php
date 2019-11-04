<?php

namespace App\Form\Handler;

use Form\Handler\FormHandler;

class ContactFormHandler extends FormHandler
{
    public function validate(array $data): void
    {
        if(!$data['title'])
        {
            $this->addError('title', 'Invalid title');
        }
        if(!$data['message'])
        {
            $this->addError('message', 'Invalid message');
        }
    }

    public function handle(array $data): void
    {


    }
}