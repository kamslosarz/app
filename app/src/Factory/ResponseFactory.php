<?php


namespace App\Factory;


use Container\Process\Process;
use Container\Process\ProcessContext;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use EventManager\EventManagerException;
use Request\Request;
use Response\Response;

class ResponseFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return Response
     * @throws EventManagerException
     */
    public function & __invoke(ProcessContext $processContext): Response
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $processContext->get('eventDispatcher');
        $eventDispatcher->dispatch();
        /** @var Event $event */
        $event = $processContext->get('event');
        $eventContext = $event->getContext();

        $response = new Response(
            $event->getStringResults(),
            $eventContext->get('responseHeaders', []),
            $eventContext->get('responseCode', 200)
        );
        /** @var Request $request */
        $request = $processContext->get('request');
        $request->saveSessionAndCookie();

        return $response;
    }
}