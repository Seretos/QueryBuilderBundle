<?php
use database\QueryBuilderBundle\expression\OrExpression;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 22:32
 */
class OrExpressionTest extends PHPUnit_Framework_TestCase {
    /**
     * @var OrExpression
     */
    private $expression;

    protected function setUp () {
        $this->expression = new OrExpression();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->expression->__toString());
        $this->expression->add('test1');
        $this->assertSame('test1 ', $this->expression->__toString());
        $this->expression->add('test2');
        $this->assertSame('(test1 OR test2 )', $this->expression->__toString());
        $this->expression->add('test3');
        $this->assertSame('(test1 OR test2 OR test3 )', $this->expression->__toString());
    }
}