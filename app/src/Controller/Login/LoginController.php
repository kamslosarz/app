<?php

namespace App\Controller\Login;

use App\Controller\AbstractController\AppController;
use App\Form\LoginForm;
use FlashMessenger\FlashMessenger;
use Form\FormView\FormView;
use View\ViewException;

class LoginController extends AppController
{
    /**
     * @return string
     * @throws ViewException
     */
    public function loginAction(): string
    {
        $form = $this->getForm(LoginForm::class);
        $view = $this->getView();

        return $view->render('login/login.phtml', [
            'loginFormView' => new FormView($form)
        ]);
    }

    public function loginPostAction(): void
    {
        $request = $this->getRequest();
        $flashMessenger = $this->getFlashMessenger();
        $form = $this->getForm(LoginForm::class);

        if($form->handle($request->getPost()->get($form->getName(), [])))
        {
            $flashMessenger->add('LOGIN_SUCCESS', FlashMessenger::TYPE_SUCCESS);
        }
        else
        {
            $this->handleFormErrors($form->getErrors());
            $flashMessenger->add('LOGIN_ERROR', FlashMessenger::TYPE_ERROR);
        }

        $this->redirect('/login');
    }
}
