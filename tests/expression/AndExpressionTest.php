<?php
use database\QueryBuilderBundle\expression\AndExpression;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 22:23
 */
class AndExpressionTest extends PHPUnit_Framework_TestCase {
    /**
     * @var AndExpression
     */
    private $expression;

    protected function setUp () {
        $this->expression = new AndExpression();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->expression->__toString());
        $this->expression->add('test1');
        $this->assertSame('test1 ', $this->expression->__toString());
        $this->expression->add('test2');
        $this->assertSame('(test1 AND test2 )', $this->expression->__toString());
        $this->expression->add('test3');
        $this->assertSame('(test1 AND test2 AND test3 )', $this->expression->__toString());
    }
}