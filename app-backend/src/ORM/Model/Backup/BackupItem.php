<?php

namespace App\ORM\Model\Backup;

use App\ORM\Peer\BackupPeer;
use Orm\Model\Model;

class BackupItem extends Model
{
    use BackupPeer;

    protected function getPrimaryKey(): string
    {
        return 'id';
    }

    protected function getTableName(): string
    {
        return 'backups';
    }

    public function setName(string $name): self
    {
        $this->properties['name'] = $name;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->properties['description'] = $description;

        return $this;
    }

    public function setDate(string $date): self
    {
        $this->properties['date'] = $date;

        return $this;
    }
}