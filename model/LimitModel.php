<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:06
 */

namespace database\QueryBuilderBundle\model;


class LimitModel {
    private $limit;
    private $offset;

    /**
     * LimitModel constructor.
     */
    public function __construct () {
        $this->offset = 0;
        $this->limit = 0;
    }

    /**
     * @param int $offset
     * @param int $defaultLimit
     */
    public function setOffset ($offset, $defaultLimit = 100) {
        $this->offset = $offset;
        if ($this->limit == 0) {
            $this->limit = $defaultLimit;
        }
    }

    /**
     * @param int $limit
     */
    public function setLimit ($limit) {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = '';
        if ($this->limit > 0) {
            $str = 'LIMIT '.$this->offset.','.$this->limit.' ';
        }

        return $str;
    }
}