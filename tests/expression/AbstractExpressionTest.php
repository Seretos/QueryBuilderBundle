<?php
use database\QueryBuilderBundle\expression\AbstractExpression;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 21:37
 */
class AbstractExpressionTest extends PHPUnit_Framework_TestCase {
    /**
     * @var AbstractExpression|PHPUnit_Framework_MockObject_MockObject
     */
    private $expression;

    /**
     * @var ReflectionClass
     */
    private $expressionReflection;

    /**
     * @var ReflectionProperty
     */
    private $conditionProperty;

    public function setUp () {
        $this->expression = $this->getMockForAbstractClass(AbstractExpression::class);

        $this->expressionReflection = new ReflectionClass(AbstractExpression::class);
        $this->conditionProperty = $this->expressionReflection->getProperty('conditions');
        $this->conditionProperty->setAccessible(true);
    }

    /**
     * @test
     */
    public function add () {
        $this->assertSame([], $this->conditionProperty->getValue($this->expression));
        $this->expression->add('test');
        $this->assertSame(['test'], $this->conditionProperty->getValue($this->expression));
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $toStringMethod = $this->expressionReflection->getMethod('toString');
        $toStringMethod->setAccessible(true);

        $this->assertSame('', $toStringMethod->invokeArgs($this->expression, ['expr']));

        $this->expression->add('test1');
        $this->assertSame('test1 ', $toStringMethod->invokeArgs($this->expression, ['expr']));

        $this->expression->add('test2');
        $this->assertSame('(test1 expr test2 )', $toStringMethod->invokeArgs($this->expression, ['expr']));
    }
}