<?php

namespace App\ORM\Repository\Backup;

use App\ORM\Model\Backup\BackupItem;
use App\ORM\Peer\BackupPeer;
use Orm\Repository\Repository;

class BackupRepository extends Repository
{
    use BackupPeer;

    protected function getTableName(): string
    {
        return 'backups';
    }

    protected function getModel(): string
    {
        return BackupItem::class;
    }
}