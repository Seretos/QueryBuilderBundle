<?php
use database\QueryBuilderBundle\builder\ExpressionBuilder;
use database\QueryBuilderBundle\builder\QueryBuilder;
use database\QueryBuilderBundle\factory\QueryBuilderBundleFactory;

/**
 * Created by PhpStorm.
 * User: aappen
 * Date: 05.06.16
 * Time: 03:47
 */
class QueryBuilderBundleFactoryTest extends PHPUnit_Framework_TestCase {
    /**
     * @var QueryBuilderBundleFactory
     */
    private $factory;

    protected function setUp () {
        $this->factory = new QueryBuilderBundleFactory();
    }

    /**
     * @test
     */
    public function createQueryBuilder () {
        $result = $this->factory->createQueryBuilder();
        $this->assertInstanceOf(QueryBuilder::class, $result);
    }

    /**
     * @test
     */
    public function createExpressionBuilder () {
        $result = $this->factory->createExpressionBuilder();
        $this->assertInstanceOf(ExpressionBuilder::class, $result);
    }
}