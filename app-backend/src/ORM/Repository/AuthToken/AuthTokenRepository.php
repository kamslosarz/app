<?php

namespace App\ORM\Repository\AuthToken;

use App\ORM\Peer\AuthToken\AuthTokenPeer;
use Orm\Repository\Repository;

class AuthTokenRepository extends Repository
{
    use AuthTokenPeer;
}