<?php
use database\QueryBuilderBundle\builder\ExpressionBuilder;
use database\QueryBuilderBundle\comparison\Comparison;
use database\QueryBuilderBundle\expression\AndExpression;
use database\QueryBuilderBundle\expression\OrExpression;
use database\QueryBuilderBundle\functions\InFunction;
use database\QueryBuilderBundle\functions\LikeFunction;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 18:59
 */
class ExpressionBuilderTest extends PHPUnit_Framework_TestCase {
    /**
     * @var ExpressionBuilder
     */
    private $expression;

    protected function setUp () {
        $this->expression = new ExpressionBuilder();
    }

    /**
     * @test
     */
    public function andX () {
        $and = $this->expression->andX('test1');

        $this->assertInstanceOf(AndExpression::class, $and);
        $this->assertSame('test1 ', $and->__toString());

        $and = $this->expression->andX('test1', 'test2');
        $this->assertInstanceOf(AndExpression::class, $and);
        $this->assertSame('(test1 AND test2 )', $and->__toString());

        $and->add('test3');
        $this->assertSame('(test1 AND test2 AND test3 )', $and->__toString());
    }

    /**
     * @test
     */
    public function orX () {
        $or = $this->expression->orX('test1');

        $this->assertInstanceOf(OrExpression::class, $or);
        $this->assertSame('test1 ', $or->__toString());

        $or = $this->expression->orX('test1', 'test2');
        $this->assertInstanceOf(OrExpression::class, $or);
        $this->assertSame('(test1 OR test2 )', $or->__toString());

        $or->add('test3');
        $this->assertSame('(test1 OR test2 OR test3 )', $or->__toString());
    }

    /**
     * @test
     */
    public function eq () {
        $eq = $this->expression->eq('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $eq);
        $this->assertSame('test2', $eq->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_EQ, $eq->getType());
        $this->assertSame('test1', $eq->getSource());
    }

    /**
     * @test
     */
    public function neq () {
        $neq = $this->expression->neq('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $neq);
        $this->assertSame('test2', $neq->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_NEQ, $neq->getType());
        $this->assertSame('test1', $neq->getSource());
    }

    /**
     * @test
     */
    public function gt () {
        $gt = $this->expression->gt('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $gt);
        $this->assertSame('test2', $gt->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_GT, $gt->getType());
        $this->assertSame('test1', $gt->getSource());
    }

    /**
     * @test
     */
    public function gte () {
        $gte = $this->expression->gte('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $gte);
        $this->assertSame('test2', $gte->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_GTE, $gte->getType());
        $this->assertSame('test1', $gte->getSource());
    }

    /**
     * @test
     */
    public function lt () {
        $lt = $this->expression->lt('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $lt);
        $this->assertSame('test2', $lt->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_LT, $lt->getType());
        $this->assertSame('test1', $lt->getSource());
    }

    /**
     * @test
     */
    public function lte () {
        $lte = $this->expression->lte('test1', 'test2');

        $this->assertInstanceOf(Comparison::class, $lte);
        $this->assertSame('test2', $lte->getTarget());
        $this->assertSame(Comparison::COMPARISON_TYPE_LTE, $lte->getType());
        $this->assertSame('test1', $lte->getSource());
    }

    /**
     * @test
     */
    public function isNullMethod () {
        $isNull = $this->expression->isNull('test1');

        $this->assertInstanceOf(Comparison::class, $isNull);
        $this->assertSame('test1', $isNull->getSource());
        $this->assertSame(Comparison::COMPARISON_TYPE_ISNULL, $isNull->getType());
    }

    /**
     * @test
     */
    public function isNotNullMethod () {
        $isNotNull = $this->expression->isNotNull('test1');

        $this->assertInstanceOf(Comparison::class, $isNotNull);
        $this->assertSame('test1', $isNotNull->getSource());
        $this->assertSame(Comparison::COMPARISON_TYPE_ISNOTNULL, $isNotNull->getType());
    }

    /**
     * @test
     */
    public function inMethod () {
        $inExpr = $this->expression->in('test1', 'test2');

        $this->assertInstanceOf(InFunction::class, $inExpr);
        $this->assertSame('test1 IN(test2)', $inExpr->__toString());
    }

    /**
     * @test
     */
    public function likeMethod () {
        $likeExpr = $this->expression->like('test1', 'test2');

        $this->assertInstanceOf(LikeFunction::class, $likeExpr);
        $this->assertSame('test1 LIKE test2', $likeExpr->__toString());
    }
}