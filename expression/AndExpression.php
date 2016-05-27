<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 19:58
 */

namespace database\QueryBuilderBundle\expression;

class AndExpression extends AbstractExpression {
    /**
     * @return string
     */
    public function __toString () {
        return $this->toString('AND');
    }
}