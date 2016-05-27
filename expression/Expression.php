<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 18:24
 */

namespace database\QueryBuilderBundle\expression;


use database\QueryBuilderBundle\comparison\Comparison;
use database\QueryBuilderBundle\functions\InFunction;
use database\QueryBuilderBundle\functions\LikeFunction;

class Expression {
    /**
     * @param string|null $condition1
     * @param string|null $condition2
     *
     * @return AndExpression
     */
    public function andX ($condition1 = null, $condition2 = null) {
        $and = new AndExpression();
        $and->add($condition1);
        $and->add($condition2);

        return $and;
    }

    /**
     * @param string|null $condition1
     * @param string|null $condition2
     *
     * @return OrExpression
     */
    public function orX ($condition1 = null, $condition2 = null) {
        $or = new OrExpression();
        $or->add($condition1);
        $or->add($condition2);

        return $or;
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function eq ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_EQ);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function neq ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_NEQ);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function gt ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_GT);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function gte ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_GTE);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function lt ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_LT);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return Comparison
     */
    public function lte ($x, $y) {
        return new Comparison($x, $y, Comparison::COMPARISON_TYPE_LTE);
    }

    /**
     * @param string $x
     *
     * @return Comparison
     */
    public function isNull ($x) {
        return new Comparison($x, null, Comparison::COMPARISON_TYPE_ISNULL);
    }

    /**
     * @param string $x
     *
     * @return Comparison
     */
    public function isNotNull ($x) {
        return new Comparison($x, null, Comparison::COMPARISON_TYPE_ISNOTNULL);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return InFunction
     */
    public function in ($x, $y) {
        return new InFunction($x, $y);
    }

    /**
     * @param string $x
     * @param string $y
     *
     * @return LikeFunction
     */
    public function like ($x, $y) {
        return new LikeFunction($x, $y);
    }
}