<?php

namespace App\Response;

class ListResponse extends JsonResponse
{
    protected int $total;
    protected int $perPage;
    protected int $itemsCount;
    private int $offset;

    public function __construct(array $data, $perPage, $itemsCount, $offset, $total)
    {
        parent::__construct($data);

        $this->perPage = $perPage;
        $this->total = $total;
        $this->offset = $offset;
        $this->itemsCount = $itemsCount;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setItemsCount(int $itemsCount): self
    {
        $this->itemsCount = $itemsCount;

        return $this;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function toJson(): string
    {
        return json_encode([
            'items' => $this->data,
            'pagination' => [
                'itemsCount' => $this->itemsCount,
                'perPage' => $this->perPage,
                'total' => $this->total,
                'offset' => $this->offset
            ]
        ]);
    }
}