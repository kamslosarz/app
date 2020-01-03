<?php

namespace App\ORM\Peer\AuthToken;

use App\ORM\Model\AuthToken\AuthTokenItem;
use App\ORM\Repository\AuthToken\AuthTokenRepository;

trait AuthTokenPeer
{
    /**
     * @return string
     */
    protected function getTableName(): string
    {
        return 'auth_tokens';
    }

    /**
     * @return string
     */
    protected function getPrimaryKey(): string
    {
        return 'id';
    }

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return AuthTokenItem::class;
    }

    /**
     * @return string
     */
    protected function getRepository(): string
    {
        return AuthTokenRepository::class;
    }
}