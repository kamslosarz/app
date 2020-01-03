<?php

namespace App\ORM\Model\AuthToken;

use App\ORM\Peer\AuthToken\AuthTokenPeer;
use DateInterval;
use DateTime;
use Orm\Model\Model;

class AuthTokenItem extends Model
{
    use AuthTokenPeer;

    public function setCreatedAt(string $createdAt): self
    {
        $this->properties['created_at'] = $createdAt;

        return $this;
    }

    public function setToken(string $token): self
    {
        $this->properties['token'] = $token;

        return $this;
    }

    public function setAuthKey(string $authKey): self
    {
        $this->properties['auth_key'] = $authKey;

        return $this;
    }

    public function isExpired(): bool
    {
        $expireAt = new DateTime();
        $expireAt->setTimestamp($this->properties['created_at']);
        $expireAt->add(new DateInterval('PT15M'));

        $now = new DateTime();

        return $now->getTimestamp() >= $expireAt->getTimestamp();
    }

}