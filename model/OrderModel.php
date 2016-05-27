<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:06
 */

namespace database\QueryBuilderBundle\model;


class OrderModel {
    private $orders;

    /**
     * OrderModel constructor.
     */
    public function __construct () {
        $this->orders = [];
    }

    /**
     * @param array  $columns
     * @param string $direction
     */
    public function add ($columns, $direction) {
        $this->orders[] = ['columns' => $columns, 'direction' => $direction];
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = '';
        if (count($this->orders) > 0) {
            $str = 'ORDER BY ';
            foreach ($this->orders as $order) {
                if ($str != 'ORDER BY ') {
                    $str .= ',';
                }
                $str .= implode(',', $order['columns']).' '.$order['direction'].' ';
            }
        }

        return $str;
    }
}