<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:05
 */

namespace database\QueryBuilderBundle\model;


class HavingModel {
    /**
     * @var string[]
     */
    private $conditions;

    /**
     * HavingModel constructor.
     */
    public function __construct () {
        $this->conditions = [];
    }

    /**
     * @param array $conditions
     */
    public function set ($conditions) {
        $this->conditions = $conditions;
    }

    /**
     * @param string $condition
     */
    public function add ($condition) {
        $this->conditions[] = $condition;
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = '';
        if (count($this->conditions) > 0) {
            $str = 'HAVING '.implode(' ', $this->conditions).' ';
        }

        return $str;
    }
}