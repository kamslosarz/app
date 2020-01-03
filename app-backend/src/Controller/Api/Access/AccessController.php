<?php

namespace App\Controller\Api\Access;

use App\Controller\Api\ApiController;
use App\Controller\Api\ApiException;
use App\ORM\Model\AuthToken\AuthTokenItem;
use App\Service\TokenGenerator\TokenGenerator;
use DateTime;
use Factory\FactoryException;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DataBaseFactory;
use Orm\OrmException;
use Orm\Query\Query;
use Orm\QueryBuilder\QueryBuilder;
use Orm\QueryBuilder\QueryBuilderPeers;
use ServiceContainer\Service\Service;
use ServiceContainer\ServiceContainerException;
use Validator\Constraint\CharacterLengthConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;

class AccessController extends ApiController
{
    /**
     * @return string|null
     * @throws ApiException
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function indexAction(): ?string
    {
        $authToken = $this->getRequest()->getHeaders()->get('authToken');
        $queryBuilder = new QueryBuilder();
        $queryBuilder->select('*')
            ->from('auth_tokens')
            ->limit(1, 0)
            ->where('token', QueryBuilderPeers::IS, ':token', [
                ':token' => $authToken
            ]);

        $query = new Query($queryBuilder, DataBaseFactory::getInstance());
        $query->execute();

        if(!$query->hasResults())
        {
            $this->stopEventPropagation();
            $this->setResponseCode(401);

            return $this->jsonErrorResponse(['Invalid access token']);
        }

        $authTokenItem = new AuthTokenItem($query->getFirstResult());

        return $authTokenItem->isExpired();
    }

    /**
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     * @throws ServiceContainerException
     * @throws FactoryException
     */
    public function generateTokenAction(): string
    {
        $authKey = $this->getRequest()->getHeaders()->get('authKey');
        $constraintBuilder = new ConstraintBuilder();
        $constraintBuilder->addConstraint('authKey', CharacterLengthConstraint::class);
        if(!$this->validate(['authKey' => $authKey], $constraintBuilder))
        {
            return $this->jsonErrorResponse($this->getErrors());
        }

        /** @var TokenGenerator $tokenGenerator */
        $tokenGenerator = $this->getTokenGenerator();

        $token = $tokenGenerator->generateToken();
        $authTokenItem = new AuthTokenItem();
        $authTokenItem->setCreatedAt((new DateTime())->getTimestamp());
        $authTokenItem->setToken($token);
        $authTokenItem->setAuthKey($authKey);
        $authTokenItem->save();

        return $this->jsonResponse(['token' => $token]);
    }

    /**
     * @return TokenGenerator|Service
     * @throws ServiceContainerException
     */
    protected function getTokenGenerator()
    {
        return $this->getServiceContainer()->getService('tokenGenerator');
    }
}