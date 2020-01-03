<?php

namespace App\ORM\Repository\Backup;

use App\ORM\Peer\Backup\BackupPeer;
use Orm\Repository\Repository;

class BackupRepository extends Repository
{
    use BackupPeer;
}