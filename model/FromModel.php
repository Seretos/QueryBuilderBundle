<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:04
 */

namespace database\QueryBuilderBundle\model;


class FromModel {
    /**
     * @var string
     */
    private $table;
    /**
     * @var string
     */
    private $alias;

    /**
     * @param string $table
     * @param string $alias
     */
    public function set ($table, $alias) {
        $this->table = $table;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = 'FROM '.$this->table.' '.$this->alias.' ';

        return $str;
    }
}