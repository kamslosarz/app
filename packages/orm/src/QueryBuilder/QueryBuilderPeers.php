<?php

namespace Orm\QueryBuilder;

abstract class QueryBuilderPeers
{
    const UPDATE = 'update';
    const INSERT = 'insert';
    const SELECT = 'select';
    const DELETE = 'delete';

    const IS = '=';
    const LIKE = 'LIKE';

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';
}