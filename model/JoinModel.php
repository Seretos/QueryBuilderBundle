<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:05
 */

namespace database\QueryBuilderBundle\model;


class JoinModel {
    private $joins;

    /**
     * JoinModel constructor.
     */
    public function __construct () {
        $this->joins = [];
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $type
     * @param string $condition
     * @param string $joinType
     */
    public function add ($table, $alias, $type, $condition, $joinType = 'INNER') {
        if ($type == 'WITH') {
            $type = 'ON';
        }
        $this->joins[] = ['table' => $table,
                          'alias' => $alias,
                          'type' => $type,
                          'condition' => $condition,
                          'joinType' => $joinType];
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = '';

        foreach ($this->joins as $join) {
            $str .= $join['joinType'].' JOIN '.$join['table'].' AS '.$join['alias'].' '.$join['type'].' '.
                    $join['condition'].' ';
        }

        return $str;
    }
}