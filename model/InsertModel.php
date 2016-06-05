<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 20.05.2016
 * Time: 23:54
 */

namespace database\QueryBuilderBundle\model;


class InsertModel {
    /**
     * @var string
     */
    private $table;

    /**
     * InsertModel constructor.
     */
    public function __construct () {
        $this->table = '';
    }

    /**
     * @param string $table
     */
    public function setTable ($table) {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function __toString () {
        return 'INSERT INTO '.$this->table.' ';
    }
}