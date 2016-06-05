<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 19:59
 */

namespace database\QueryBuilderBundle\expression;


abstract class AbstractExpression {
    /**
     * @var array
     */
    protected $conditions;

    /**
     * AbstractExpression constructor.
     */
    public function __construct () {
        $this->conditions = [];
    }

    public function count () {
        return count($this->conditions);
    }

    /**
     * @param $condition
     */
    public function add ($condition) {
        if ($condition != null) {
            $this->conditions[] = $condition;
        }
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function toString ($type) {
        $result = $this->isSub('(');

        foreach ($this->conditions as $index => $condition) {
            if ($index > 0) {
                $result .= $type.' ';
            }
            $result .= $condition.' ';
        }

        return $result.$this->isSub(')');
    }

    /**
     * @param string $sub
     *
     * @return string
     */
    private function isSub ($sub) {
        if (count($this->conditions) > 1) {
            return $sub;
        }

        return '';
    }
}