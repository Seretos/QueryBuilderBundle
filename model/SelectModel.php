<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:04
 */

namespace database\QueryBuilderBundle\model;


class SelectModel {
    /**
     * @var array
     */
    private $columns;

    /**
     * SelectModel constructor.
     */
    public function __construct () {
        $this->columns = [];
    }

    /**
     * @param array $columns
     */
    public function set ($columns) {
        $this->columns = $columns;
    }

    /**
     * @param array $columns
     */
    public function add ($columns) {
        $this->columns = array_merge($this->columns, $columns);
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = 'SELECT ';
        if (count($this->columns) == 0) {
            $str .= '* ';
        } else {
            $str .= implode(',', $this->columns).' ';
        }

        return $str;
    }
}