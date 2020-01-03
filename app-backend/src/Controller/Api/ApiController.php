<?php

namespace App\Controller\Api;

use App\Controller\AbstractController\AppController;
use App\Response\JsonResponse;
use App\Response\ListResponse;
use App\Response\PaginationResponse;
use EventManager\Event\Context;

abstract class ApiController extends AppController
{
    public function __construct(Context $context)
    {
        parent::__construct($context);

        $this->context->set('jsonResponse', true);
    }

    protected function jsonErrorResponse(array $errors): string
    {
        $this->setContextJson();

        return (new JsonResponse([], JsonResponse::STATUS_ERROR, $errors))->toJson();
    }

    protected function jsonSuccessResponse(): string
    {
        return (new JsonResponse())->toJson();
    }

    protected function jsonItemResponse(array $item): string
    {
        return (new JsonResponse(['item' => $item]))->toJson();
    }

    protected function jsonResponse(array $data): string
    {
        return (new JsonResponse($data))->toJson();
    }

    protected function jsonListResponse(array $items, int $itemsCount, int $offset, int $total, int $itemsPerPage = 100): string
    {
        return (new ListResponse($items, new PaginationResponse($itemsPerPage, $itemsCount, $offset, $total)))->toJson();
    }

    private function setContextJson()
    {
        $this->context->set('jsonResponse', true);
    }
}