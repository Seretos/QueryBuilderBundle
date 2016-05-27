<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 18.05.2016
 * Time: 21:54
 */

namespace database\QueryBuilderBundle\factory;


use database\DriverBundle\connection\interfaces\ConnectionInterface;
use database\QueryBuilderBundle\builder\QueryBuilder;
use database\QueryBuilderBundle\model\DeleteModel;
use database\QueryBuilderBundle\model\FromModel;
use database\QueryBuilderBundle\model\GroupModel;
use database\QueryBuilderBundle\model\HavingModel;
use database\QueryBuilderBundle\model\InsertModel;
use database\QueryBuilderBundle\model\JoinModel;
use database\QueryBuilderBundle\model\LimitModel;
use database\QueryBuilderBundle\model\OrderModel;
use database\QueryBuilderBundle\model\SelectModel;
use database\QueryBuilderBundle\model\UpdateModel;
use database\QueryBuilderBundle\model\ValuesModel;
use database\QueryBuilderBundle\model\WhereModel;
use database\QueryBundle\factory\QueryFactory;
use database\QueryBundle\query\Query;

class QueryBuilderFactory {
    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * QueryBuilderFactory constructor.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct (ConnectionInterface $connection) {
        $this->queryFactory = new QueryFactory($connection);
    }

    /**
     * @param QueryBuilder $builder
     * @param array        $parameters
     *
     * @return Query
     * @throws \database\QueryBuilderBundle\exception\QueryBuilderException
     */
    public function createQuery (QueryBuilder $builder, $parameters = []) {
        $query = new Query($this->queryFactory, $builder->__toString());
        $query->setParameters($parameters);

        return $query;
    }

    /**
     * @param $columns
     *
     * @return SelectModel
     */
    public function createSelectModel ($columns) {
        $model = new SelectModel();
        $model->set($columns);

        return $model;
    }

    /**
     * @param $table
     *
     * @return InsertModel
     */
    public function createInsertModel ($table) {
        $model = new InsertModel();
        $model->setTable($table);

        return $model;
    }

    /**
     * @param $table
     *
     * @return UpdateModel
     */
    public function createUpdateModel ($table) {
        $model = new UpdateModel();
        $model->setTable($table);

        return $model;
    }

    /**
     * @param $table
     *
     * @return DeleteModel
     */
    public function createDeleteModel ($table) {
        $model = new DeleteModel();
        $model->setTable($table);

        return $model;
    }

    /**
     * @param $table
     * @param $alias
     *
     * @return FromModel
     */
    public function createFromModel ($table, $alias) {
        $model = new FromModel();
        $model->set($table, $alias);

        return $model;
    }

    /**
     * @return JoinModel
     */
    public function createJoinModel () {
        $model = new JoinModel();

        return $model;
    }

    /**
     * @return WhereModel
     */
    public function createWhereModel () {
        $model = new WhereModel();

        return $model;
    }

    /**
     * @return GroupModel
     */
    public function createGroupModel () {
        $model = new GroupModel();

        return $model;
    }

    /**
     * @return HavingModel
     */
    public function createHavingModel () {
        $model = new HavingModel();

        return $model;
    }

    /**
     * @return OrderModel
     */
    public function createOrderModel () {
        $model = new OrderModel();

        return $model;
    }

    /**
     * @return LimitModel
     */
    public function createLimitModel () {
        $model = new LimitModel();

        return $model;
    }

    /**
     * @return ValuesModel
     */
    public function createValuesModel () {
        $model = new ValuesModel();

        return $model;
    }
}