<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 18:28
 */

namespace database\QueryBuilderBundle\comparison;


use database\QueryBuilderBundle\exception\QueryBuilderException;

class Comparison {
    const COMPARISON_TYPE_EQ        = 'EQ';
    const COMPARISON_TYPE_NEQ       = 'NEQ';
    const COMPARISON_TYPE_LT        = 'LT';
    const COMPARISON_TYPE_LTE       = 'LTE';
    const COMPARISON_TYPE_GT        = 'GT';
    const COMPARISON_TYPE_GTE       = 'GTE';
    const COMPARISON_TYPE_ISNULL    = 'ISNULL';
    const COMPARISON_TYPE_ISNOTNULL = 'ISNOTNULL';
    const COMPARISON_TYPE           = [self::COMPARISON_TYPE_EQ => '=',
                                       self::COMPARISON_TYPE_NEQ => '!=',
                                       self::COMPARISON_TYPE_LT => '<',
                                       self::COMPARISON_TYPE_LTE => '<=',
                                       self::COMPARISON_TYPE_GT => '>',
                                       self::COMPARISON_TYPE_GTE => '>=',
                                       self::COMPARISON_TYPE_ISNULL => 'IS NULL',
                                       self::COMPARISON_TYPE_ISNOTNULL => 'IS NOT NULL'];

    private $source;
    private $target;
    private $type;

    /**
     * Comparison constructor.
     *
     * @param string      $source
     * @param string|null $target
     * @param string|null $type
     */
    public function __construct ($source, $target = null, $type = null) {
        $this->source = $source;
        $this->target = $target;
        $this->type = $type;
    }

    /**
     * @return string
     * @throws QueryBuilderException
     */
    public function __toString () {
        if (!array_key_exists($this->type, self::COMPARISON_TYPE)) {
            throw new QueryBuilderException('invalid comparison type '.$this->type);
        }

        $result = $this->source.' '.self::COMPARISON_TYPE[$this->type].' ';

        if (!($this->type == 'ISNULL' || $this->type == 'ISNOTNULL')) {
            $result .= $this->target.' ';
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getSource () {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getTarget () {
        return $this->target;
    }

    /**
     * @param string $target
     *
     * @return Comparison
     */
    public function setTarget ($target) {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getType () {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Comparison
     */
    public function setType ($type) {
        $this->type = $type;

        return $this;
    }


}