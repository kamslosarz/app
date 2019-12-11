<?php

namespace App\Controller\Contact;

use App\Controller\AbstractController\AppController;
use App\Form\Contact\ContactForm;
use Form\FormView\FormView;
use View\ViewException;

class ContactController extends AppController
{
    /**
     * @return string
     * @throws ViewException
     */
    public function indexAction(): string
    {
        $form = $this->getform(ContactForm::class);

        return $this->getView()->render('contact/contact.phtml', [
            'formView' => new FormView($form)
        ]);
    }

    public function indexPostAction(): void
    {
    }
}
