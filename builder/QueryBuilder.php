<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:07
 */

namespace database\QueryBuilderBundle\builder;


use database\QueryBuilderBundle\exception\QueryBuilderException;
use database\QueryBuilderBundle\factory\QueryBuilderFactory;
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
use database\QueryBundle\query\Query;

class QueryBuilder {
    const SELECT = 1;
    const UPDATE = 2;
    const INSERT = 3;
    const DELETE = 4;

    /**
     * @var SelectModel
     */
    private $select;
    /**
     * @var FromModel
     */
    private $from;
    /**
     * @var JoinModel
     */
    private $join;
    /**
     * @var WhereModel
     */
    private $where;
    /**
     * @var GroupModel
     */
    private $group;
    /**
     * @var HavingModel
     */
    private $having;
    /**
     * @var OrderModel
     */
    private $order;
    /**
     * @var ValuesModel
     */
    private $values;
    /**
     * @var LimitModel
     */
    private $limit;

    /**
     * @var InsertModel
     */
    private $insert;

    /**
     * @var UpdateModel
     */
    private $update;

    /**
     * @var DeleteModel
     */
    private $delete;

    /**
     * @var QueryBuilderFactory
     */
    private $factory;

    /**
     * @var int
     */
    private $queryType;

    /**
     * @var array
     */
    private $parameters;

    /**
     * QueryBuilder constructor.
     *
     * @param QueryBuilderFactory $factory
     */
    public function __construct (QueryBuilderFactory $factory) {
        $this->factory = $factory;
        $this->select = null;
        $this->from = null;
        $this->join = null;
        $this->where = null;
        $this->group = null;
        $this->having = null;
        $this->order = null;
        $this->values = null;
        $this->limit = null;
        $this->insert = null;
        $this->update = null;
        $this->delete = null;
        $this->parameters = [];
        $this->queryType = null;
    }

    /**
     * @param string|array $columns
     *
     * @return $this
     */
    public function select ($columns) {
        $this->select = $this->factory->createSelectModel($this->valueToArray($columns));
        $this->initJoin();
        $this->initWhere();
        $this->initGroup();
        $this->initHaving();
        $this->initOrder();
        $this->initLimit();
        $this->queryType = self::SELECT;

        return $this;
    }

    /**
     * @param string|array $columns
     *
     * @return $this
     */
    public function addSelect ($columns) {
        if ($this->select == null) {
            $this->select($this->valueToArray($columns));
        } else {
            $this->select->add($this->valueToArray($columns));
        }

        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function insert ($table) {
        $this->insert = $this->factory->createInsertModel($table);
        $this->initValues();
        $this->queryType = self::INSERT;

        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function update ($table) {
        $this->update = $this->factory->createUpdateModel($table);
        $this->initValues();
        $this->initWhere();
        $this->queryType = self::UPDATE;

        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function delete ($table) {
        $this->delete = $this->factory->createDeleteModel($table);
        $this->initWhere();
        $this->queryType = self::DELETE;

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     *
     * @return $this
     */
    public function from ($table, $alias) {
        $this->from = $this->factory->createFromModel($table, $alias);

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $type
     * @param string $condition
     * @param string $joinType
     *
     * @return $this
     */
    public function join ($table, $alias, $type, $condition, $joinType = 'INNER') {
        $this->initJoin();
        $this->join->add($table, $alias, $type, $condition, $joinType);

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $type
     * @param string $condition
     *
     * @return $this
     */
    public function leftJoin ($table, $alias, $type, $condition) {
        $this->join($table, $alias, $type, $condition, 'LEFT');

        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $type
     * @param string $condition
     *
     * @return $this
     */
    public function innerJoin ($table, $alias, $type, $condition) {
        $this->join($table, $alias, $type, $condition, 'INNER');

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function where ($condition) {
        $this->initWhere();
        $this->where->set([$condition]);

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function andWhere ($condition) {
        $this->initWhere();
        $this->where->add('AND '.$condition);

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function orWhere ($condition) {
        $this->initWhere();
        $this->where->add('OR '.$condition);

        return $this;
    }

    /**
     * @param string|array $conditions
     *
     * @return $this
     */
    public function groupBy ($conditions) {
        $this->initGroup();
        $this->group->set($this->valueToArray($conditions));

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function addGroupBy ($condition) {
        $this->initGroup();
        $this->group->add($condition);

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function having ($condition) {
        $this->initHaving();
        $this->having->set([$condition]);

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function andHaving ($condition) {
        $this->initHaving();
        $this->having->add('AND '.$condition);

        return $this;
    }

    /**
     * @param string $condition
     *
     * @return $this
     */
    public function orHaving ($condition) {
        $this->initHaving();
        $this->having->add('OR '.$condition);

        return $this;
    }

    /**
     * @param string|array $columns
     * @param string       $direction
     *
     * @return $this
     */
    public function orderBy ($columns, $direction = 'ASC') {
        $this->initOrder();
        $this->order->add($this->valueToArray($columns), $direction);

        return $this;
    }

    /**
     * @param string|array $columns
     * @param string       $direction
     *
     * @return QueryBuilder
     */
    public function addOrderBy ($columns, $direction = 'ASC') {
        return $this->orderBy($columns, $direction);
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function setFirstResult ($offset = 0) {
        $this->initLimit();
        $this->limit->setOffset($offset);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setMaxResult ($limit = 100) {
        $this->initLimit();
        $this->limit->setLimit($limit);

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function set ($values) {
        $this->initValues();
        $this->values->set($this->parseValues($values));

        return $this;
    }

    /**
     * @return Query
     */
    public function buildQuery () {
        return $this->factory->createQuery($this, $this->parameters);
    }

    /**
     * @return string
     * @throws QueryBuilderException
     */
    public function __toString () {
        switch ($this->queryType) {
            case self::SELECT:
                $str = $this->select.$this->from.$this->join.$this->where.$this->group.$this->having.$this->order.
                       $this->limit;
                break;
            case self::INSERT:
                $str = $this->insert.$this->values;
                break;
            case self::UPDATE:
                $str = $this->update.$this->values->update().$this->where;
                break;
            case self::DELETE:
                $str = $this->delete.$this->where;
                break;
            default:
                throw new QueryBuilderException('invalid query type. call select(),update(),delete() or insert()');
                break;
        }

        return $str;
    }

    private function valueToArray ($value) {
        if (!is_array($value)) {
            return [$value];
        }

        return $value;
    }

    private function parseValues ($values) {
        foreach ($values as $key => $value) {
            $this->parameters[$key] = $value;
            $values[$key] = ':'.$key;
        }

        return $values;
    }

    private function initJoin () {
        if ($this->join == null) {
            $this->join = $this->factory->createJoinModel();
        }
    }

    private function initWhere () {
        if ($this->where == null) {
            $this->where = $this->factory->createWhereModel();
        }
    }

    private function initGroup () {
        if ($this->group == null) {
            $this->group = $this->factory->createGroupModel();
        }
    }

    private function initHaving () {
        if ($this->having == null) {
            $this->having = $this->factory->createHavingModel();
        }
    }

    private function initOrder () {
        if ($this->order == null) {
            $this->order = $this->factory->createOrderModel();
        }
    }

    private function initLimit () {
        if ($this->limit == null) {
            $this->limit = $this->factory->createLimitModel();
        }
    }

    private function initValues () {
        if ($this->values == null) {
            $this->values = $this->factory->createValuesModel();
        }
    }
}