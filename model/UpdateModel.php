<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 21.05.2016
 * Time: 01:55
 */

namespace database\QueryBuilderBundle\model;


class UpdateModel {
    /**
     * @var string
     */
    private $table;

    /**
     * UpdateModel constructor.
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
        $str = 'UPDATE '.$this->table.' SET ';

        return $str;
    }
}