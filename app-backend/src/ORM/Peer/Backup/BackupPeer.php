<?php

namespace App\ORM\Peer\Backup;

use App\ORM\Model\Backup\BackupItem;
use App\ORM\Repository\Backup\BackupRepository;

trait BackupPeer
{
    /**
     * @return string
     */
    protected function getTableName(): string
    {
        return 'backups';
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
        return BackupItem::class;
    }

    /**
     * @return string
     */
    protected function getRepository(): string
    {
        return BackupRepository::class;
    }
}