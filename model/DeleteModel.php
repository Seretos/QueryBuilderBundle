<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 21.05.2016
 * Time: 01:57
 */

namespace database\QueryBuilderBundle\model;


class DeleteModel {
    private $table;

    /**
     * DeleteModel constructor.
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
        return 'DELETE FROM '.$this->table.' ';
    }
}