<?php
use database\DriverBundle\connection\interfaces\ConnectionInterface;
use database\QueryBuilderBundle\builder\QueryBuilder;
use database\QueryBuilderBundle\factory\QueryBuilderFactory;
use database\QueryBuilderBundle\model\DeleteModel;
use database\QueryBuilderBundle\model\FromModel;
use database\QueryBuilderBundle\model\GroupModel;
use database\QueryBuilderBundle\model\HavingModel;
use database\QueryBuilderBundle\model\InsertModel;
use database\QueryBuilderBundle\model\JoinModel;
use database\QueryBuilderBundle\model\LimitModel;
use database\QueryBuilderBundle\model\OrderModel;
use database\QueryBuilderBundle\model\SelectModel;
use database\QueryBuilderBundle\model\UpdateModel;
use database\QueryBuilderBundle\model\ValuesModel;
use database\QueryBuilderBundle\model\WhereModel;
use database\QueryBundle\factory\QueryFactory;
use database\QueryBundle\parameter\StringParameter;
use database\QueryBundle\query\Query;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:19
 */
class QueryBuilderFactoryTest extends PHPUnit_Framework_TestCase {
    /**
     * @var QueryBuilderFactory
     */
    private $factory;

    /**
     * @var ConnectionInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $mockConnection;

    /**
     * @var ReflectionProperty
     */
    private $queryFactoryProperty;

    protected function setUp () {
        $this->mockConnection = $this->getMockBuilder(ConnectionInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $this->factory = new QueryBuilderFactory($this->mockConnection);

        $factoryReflection = new ReflectionClass(QueryBuilderFactory::class);
        $this->queryFactoryProperty = $factoryReflection->getProperty('queryFactory');
        $this->queryFactoryProperty->setAccessible(true);
    }

    /**
     * @test
     */
    public function queryFactory () {
        /* @var $queryFactory QueryFactory */
        $queryFactory = $this->queryFactoryProperty->getValue($this->factory);
        $this->assertInstanceOf(QueryFactory::class, $queryFactory);
        $this->assertSame($this->mockConnection, $queryFactory->getConnection());
    }

    /**
     * @test
     */
    public function createQuery () {
        /* @var $mockBuilder QueryBuilder|PHPUnit_Framework_MockObject_MockObject */
        $mockBuilder = $this->getMockBuilder(QueryBuilder::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $mockBuilder->expects($this->once())
                    ->method('__toString')
                    ->will($this->returnValue('example sql'));

        $queryReflection = new ReflectionClass(Query::class);
        $queryFactoryProperty = $queryReflection->getProperty('factory');
        $queryFactoryProperty->setAccessible(true);
        $queryParametersProperty = $queryReflection->getProperty('parameters');
        $queryParametersProperty->setAccessible(true);

        $query = $this->factory->createQuery($mockBuilder, ['param1' => 'value1']);

        $this->assertInstanceOf(Query::class, $query);
        $this->assertSame('example sql', $query->getSql());
        $this->assertSame($this->queryFactoryProperty->getValue($this->factory),
                          $queryFactoryProperty->getValue($query));
        $this->assertInstanceOf(StringParameter::class,
                                $queryParametersProperty->getValue($query)['param1']);
    }

    /**
     * @test
     */
    public function createSelectModel () {
        $model = $this->factory->createSelectModel(['column1']);
        $this->assertInstanceOf(SelectModel::class, $model);
        $this->assertSame('SELECT column1 ', $model->__toString());
    }

    /**
     * @test
     */
    public function createInsertModel () {
        $model = $this->factory->createInsertModel('example1');
        $this->assertInstanceOf(InsertModel::class, $model);
        $this->assertSame('INSERT INTO example1 ', $model->__toString());
    }

    /**
     * @test
     */
    public function createUpdateModel () {
        $model = $this->factory->createUpdateModel('example1');
        $this->assertInstanceOf(UpdateModel::class, $model);
        $this->assertSame('UPDATE example1 SET ', $model->__toString());
    }

    /**
     * @test
     */
    public function createDeleteModel () {
        $model = $this->factory->createDeleteModel('example1');
        $this->assertInstanceOf(DeleteModel::class, $model);
        $this->assertSame('DELETE FROM example1 ', $model->__toString());
    }

    /**
     * @test
     */
    public function createFromModel () {
        $model = $this->factory->createFromModel('example1', 'e1');
        $this->assertInstanceOf(FromModel::class, $model);
        $this->assertSame('FROM example1 e1 ', $model->__toString());
    }

    /**
     * @test
     */
    public function createJoinModel () {
        $model = $this->factory->createJoinModel();
        $this->assertInstanceOf(JoinModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createWhereModel () {
        $model = $this->factory->createWhereModel();
        $this->assertInstanceOf(WhereModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createGroupModel () {
        $model = $this->factory->createGroupModel();
        $this->assertInstanceOf(GroupModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createHavingModel () {
        $model = $this->factory->createHavingModel();
        $this->assertInstanceOf(HavingModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createOrderModel () {
        $model = $this->factory->createOrderModel();
        $this->assertInstanceOf(OrderModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createLimitModel () {
        $model = $this->factory->createLimitModel();
        $this->assertInstanceOf(LimitModel::class, $model);
        $this->assertSame('', $model->__toString());
    }

    /**
     * @test
     */
    public function createValuesModel () {
        $model = $this->factory->createValuesModel();
        $this->assertInstanceOf(ValuesModel::class, $model);
        $this->assertSame('() VALUES() ', $model->__toString());
    }
}