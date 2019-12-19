<?php

namespace Orm\Peer;

abstract class Peer
{
    /**
     * @return string
     */
    abstract protected function getTableName(): string;

    /**
     * @return string
     */
    abstract protected function getPrimaryKey(): string;

    /**
     * @return string
     */
    abstract protected function getModel(): string;

    /**
     * @return string
     */
    abstract protected function getRepository(): string ;
}