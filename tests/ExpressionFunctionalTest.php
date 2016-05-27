<?php
use database\QueryBuilderBundle\expression\Expression;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 20:12
 */
class ExpressionFunctionalTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Expression
     */
    private $expression;

    protected function setUp () {
        $this->expression = new Expression();
    }

    /**
     * @test
     */
    public function andX () {
        $this->assertSame('(test1 = 1  AND test2 = 2  )',
                          $this->expression->andX($this->expression->eq('test1', '1'),
                                                  $this->expression->eq('test2', '2'))
                                           ->__toString());
    }

    /**
     * @test
     */
    public function orX () {
        $this->assertSame('(test1 = 1  OR test2 = 2  )',
                          $this->expression->orX($this->expression->eq('test1', '1'),
                                                 $this->expression->eq('test2', '2'))
                                           ->__toString());
    }

    /**
     * @test
     */
    public function andX_and_orX () {
        $andExpression = $this->expression->andX($this->expression->eq('test1', '1'));
        $this->assertSame('test1 = 1  ', $andExpression->__toString());

        $andExpression->add($this->expression->neq('test2', '2'));
        $this->assertSame('(test1 = 1  AND test2 != 2  )', $andExpression->__toString());

        $andExpression->add($this->expression->orX($this->expression->gt('test3', '3'),
                                                   $this->expression->lt('test3', '1')));
        $this->assertSame('(test1 = 1  AND test2 != 2  AND (test3 > 3  OR test3 < 1  ) )', $andExpression->__toString());
    }

    /**
     * @test
     */
    public function eq () {
        $this->assertSame('test1 = test2 ',
                          $this->expression->eq('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function neq () {
        $this->assertSame('test1 != test2 ',
                          $this->expression->neq('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function gt () {
        $this->assertSame('test1 > test2 ',
                          $this->expression->gt('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function gte () {
        $this->assertSame('test1 >= test2 ',
                          $this->expression->gte('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function lt () {
        $this->assertSame('test1 < test2 ',
                          $this->expression->lt('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function lte () {
        $this->assertSame('test1 <= test2 ',
                          $this->expression->lte('test1', 'test2')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function isNullMethod () {
        $this->assertSame('test1 IS NULL ',
                          $this->expression->isNull('test1')
                                           ->__toString());
    }

    /**
     * @test
     */
    public function isNotNull () {
        $this->assertSame('test1 IS NOT NULL ',
                          $this->expression->isNotNull('test1')
                                           ->__toString());
    }
}