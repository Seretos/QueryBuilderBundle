<?php
/**
 * Created by PhpStorm.
 * User: aappen
 * Date: 05.06.16
 * Time: 03:17
 */

namespace database\QueryBuilderBundle\factory;


use database\QueryBuilderBundle\builder\ExpressionBuilder;
use database\QueryBuilderBundle\builder\QueryBuilder;

class QueryBuilderBundleFactory {
    /**
     * @var QueryBuilderFactory
     */
    private $factory;

    public function __construct () {
        $this->factory = new QueryBuilderFactory();
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder () {
        return new QueryBuilder($this->factory);
    }

    /**
     * @return ExpressionBuilder
     */
    public function createExpressionBuilder () {
        return new ExpressionBuilder();
    }
}