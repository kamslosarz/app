<?php

namespace App\Factory;

use App\Response\JsonResponse;
use Container\Process\Process;
use Container\Process\ProcessContext;
use Exception;
use Response\Response;

class JsonErrorResponseFactory extends Process
{
    public function & __invoke(ProcessContext $processContext)
    {
        /** @var Exception $exception */
        $exception = $processContext->get('containerException');
        $jsonResponse = new JsonResponse([], JsonResponse::STATUS_ERROR, [
            sprintf('%s %s', $exception->getMessage(), $exception->getTraceAsString()),
        ]);
        $response = new Response(
            $jsonResponse->toJson(),
            [],
            500,
        );
        $processContext->remove('containerException');

        return $response;
    }
}