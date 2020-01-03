<?php

namespace App\Controller\Api\Backups;

use App\ORM\Repository\Backup\BackupRepository;
use Collection\Collection;
use Orm\DataBase\DatabaseAdapter\DataBaseAdapterException;
use Orm\DataBase\DataBaseFactory;
use Orm\OrmException;
use Orm\Query\Query;
use Orm\QueryBuilder\QueryBuilder;
use Orm\QueryBuilder\QueryBuilderPeers;

class BackupsListController extends BackupController
{
    const ITEMS_PER_PAGE = 10;

    /**
     * @param int $offset
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function listAction(int $offset = 0): string
    {
        $backupRepository = new BackupRepository();
        $items = $backupRepository->find(10, $offset);

        return $this->jsonListResponse($items->__toArray(), $items->count(), $offset, $backupRepository->count(), self::ITEMS_PER_PAGE);
    }

    /**
     * @param int $offset
     * @return string
     * @throws DataBaseAdapterException
     * @throws OrmException
     */
    public function searchAction(int $offset = 0)
    {
        $keyword = $this->getRequest()->getPost()->get('keyword');
        if(!$keyword)
        {
            return $this->listAction();
        }

        $queryBuilder = new QueryBuilder();
        $queryBuilder->select('*')
            ->from('backups')
            ->limit(10, $offset)
            ->order('name', QueryBuilderPeers::ORDER_ASC)
            ->where('name', QueryBuilderPeers::LIKE, ':keyword', [':keyword' => '%' . $keyword . '%']);
        $query = new Query($queryBuilder, DataBaseFactory::getInstance());

        /** @var Collection $items */
        $items = new Collection($query->execute()->getResults());
        $countQuery = new Query($queryBuilder->rebuildAsCountQuery(), DataBaseFactory::getInstance());
        $count = $countQuery->execute()->getFirstResult()['count'];

        return $this->jsonListResponse($items->__toArray(), $items->count(), $offset, $count, self::ITEMS_PER_PAGE);
    }
}