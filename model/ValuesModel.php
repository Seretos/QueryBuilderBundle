<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 15.05.2016
 * Time: 13:06
 */

namespace database\QueryBuilderBundle\model;


class ValuesModel {
    /**
     * @var array
     */
    private $values;

    /**
     * ValuesModel constructor.
     */
    public function __construct () {
        $this->values = [];
    }

    /**
     * @param array $values
     */
    public function set ($values) {
        $this->values = $values;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function add ($name, $value) {
        $this->values[$name] = $value;
    }

    /**
     * @return string
     */
    public function update () {
        $str = implode(',',
                       array_map(function ($key, $value) {
                           return $key.' = '.$value.' ';
                       },
                           array_keys($this->values),
                           $this->values));

        return $str;
    }

    /**
     * @return string
     */
    public function __toString () {
        $str = '('.implode(',', array_keys($this->values)).') ';
        $str .= 'VALUES('.implode(',', $this->values).') ';

        return $str;
    }
}