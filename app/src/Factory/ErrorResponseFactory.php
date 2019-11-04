<?php

namespace App\Factory;

use Container\Process\Process;
use Container\Process\ProcessContext;
use Response\Response;
use View\View;
use View\ViewException;

class ErrorResponseFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return Response
     * @throws ViewException
     */
    public function & __invoke(ProcessContext $processContext): Response
    {
        /** @var View $view */
        $view = $processContext->get('view');
        $response = new Response(
            $view->render('error/index.phtml', [
                'exception' => $processContext->get('containerException')
            ]),
            [],
            500,
        );
        $processContext->remove('containerException');


        return $response;
    }
}