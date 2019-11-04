<?php

namespace App\Controller;

use App\Form\ContactForm;
use Form\FormView\FormView;
use View\ViewException;

class ContactController extends Controller
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
