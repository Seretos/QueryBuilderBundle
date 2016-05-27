<?php
use database\QueryBuilderBundle\Exception\QueryBuilderException;
use database\QueryBuilderBundle\comparison\Comparison;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 01.05.2016
 * Time: 18:38
 */
class ComparisonTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Comparison
     */
    private $comparison;

    protected function setUp () {
        $this->comparison = new Comparison('test1');
    }

    /**
     * @test
     */
    public function setTarget () {
        $this->assertSame($this->comparison, $this->comparison->setTarget('test'));
        $this->assertSame('test', $this->comparison->getTarget());
    }

    /**
     * @test
     */
    public function setType () {
        $this->assertSame($this->comparison, $this->comparison->setType('test'));
        $this->assertSame('test', $this->comparison->getType());
    }

    /**
     * @test
     */
    public function eq () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_EQ)
                         ->setTarget('test2');

        $this->assertEquals('test1 = test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function neq () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_NEQ)
                         ->setTarget('test2');

        $this->assertEquals('test1 != test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function gt () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_GT)
                         ->setTarget('test2');

        $this->assertEquals('test1 > test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function gte () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_GTE)
                         ->setTarget('test2');

        $this->assertEquals('test1 >= test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function lt () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_LT)
                         ->setTarget('test2');

        $this->assertEquals('test1 < test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function lte () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_LTE)
                         ->setTarget('test2');

        $this->assertEquals('test1 <= test2 ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function isNullMethod () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_ISNULL);

        $this->assertEquals('test1 IS NULL ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function isNotNullMethod () {
        $this->comparison->setType(Comparison::COMPARISON_TYPE_ISNOTNULL);

        $this->assertEquals('test1 IS NOT NULL ', $this->comparison->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->comparison->setType('test');
        $this->setExpectedExceptionRegExp(QueryBuilderException::class);
        $this->comparison->__toString();
    }
}