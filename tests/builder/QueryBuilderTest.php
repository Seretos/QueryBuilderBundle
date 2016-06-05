<?php
use database\QueryBuilderBundle\builder\QueryBuilder;
use database\QueryBuilderBundle\exception\QueryBuilderException;
use database\QueryBuilderBundle\factory\QueryBuilderFactory;
use database\QueryBuilderBundle\model\GroupModel;
use database\QueryBuilderBundle\model\HavingModel;
use database\QueryBuilderBundle\model\JoinModel;
use database\QueryBuilderBundle\model\LimitModel;
use database\QueryBuilderBundle\model\OrderModel;
use database\QueryBuilderBundle\model\SelectModel;
use database\QueryBuilderBundle\model\ValuesModel;
use database\QueryBuilderBundle\model\WhereModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 03:47
 */
class QueryBuilderTest extends PHPUnit_Framework_TestCase {
    /**
     * @var QueryBuilderFactory|PHPUnit_Framework_MockObject_MockObject
     */
    private $mockFactory;

    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @var ReflectionProperty
     */
    private $factoryProperty;
    /**
     * @var ReflectionProperty
     */
    private $selectProperty;
    /**
     * @var ReflectionProperty
     */
    private $fromProperty;
    /**
     * @var ReflectionProperty
     */
    private $joinProperty;
    /**
     * @var ReflectionProperty
     */
    private $whereProperty;
    /**
     * @var ReflectionProperty
     */
    private $groupProperty;
    /**
     * @var ReflectionProperty
     */
    private $havingProperty;
    /**
     * @var ReflectionProperty
     */
    private $orderProperty;
    /**
     * @var ReflectionProperty
     */
    private $valuesProperty;
    /**
     * @var ReflectionProperty
     */
    private $limitProperty;
    /**
     * @var ReflectionProperty
     */
    private $insertProperty;
    /**
     * @var ReflectionProperty
     */
    private $updateProperty;
    /**
     * @var ReflectionProperty
     */
    private $deleteProperty;
    /**
     * @var ReflectionProperty
     */
    private $parametersProperty;
    /**
     * @var ReflectionProperty
     */
    private $queryTypeProperty;

    protected function setUp () {
        $this->mockFactory = $this->getMockBuilder(QueryBuilderFactory::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        $this->builder = new QueryBuilder($this->mockFactory);

        $builderReflection = new ReflectionClass(QueryBuilder::class);
        $this->factoryProperty = $builderReflection->getProperty('factory');
        $this->selectProperty = $builderReflection->getProperty('select');
        $this->fromProperty = $builderReflection->getProperty('from');
        $this->joinProperty = $builderReflection->getProperty('join');
        $this->whereProperty = $builderReflection->getProperty('where');
        $this->groupProperty = $builderReflection->getProperty('group');
        $this->havingProperty = $builderReflection->getProperty('having');
        $this->orderProperty = $builderReflection->getProperty('order');
        $this->valuesProperty = $builderReflection->getProperty('values');
        $this->limitProperty = $builderReflection->getProperty('limit');
        $this->insertProperty = $builderReflection->getProperty('insert');
        $this->updateProperty = $builderReflection->getProperty('update');
        $this->deleteProperty = $builderReflection->getProperty('delete');
        $this->parametersProperty = $builderReflection->getProperty('parameters');
        $this->queryTypeProperty = $builderReflection->getProperty('queryType');

        $this->factoryProperty->setAccessible(true);
        $this->selectProperty->setAccessible(true);
        $this->fromProperty->setAccessible(true);
        $this->joinProperty->setAccessible(true);
        $this->whereProperty->setAccessible(true);
        $this->groupProperty->setAccessible(true);
        $this->havingProperty->setAccessible(true);
        $this->orderProperty->setAccessible(true);
        $this->valuesProperty->setAccessible(true);
        $this->limitProperty->setAccessible(true);
        $this->insertProperty->setAccessible(true);
        $this->updateProperty->setAccessible(true);
        $this->deleteProperty->setAccessible(true);
        $this->parametersProperty->setAccessible(true);
        $this->queryTypeProperty->setAccessible(true);
    }

    /**
     * @test
     */
    public function construct () {
        $this->assertSame($this->mockFactory, $this->factoryProperty->getValue($this->builder));
        $this->assertSame(null, $this->selectProperty->getValue($this->builder));
        $this->assertSame(null, $this->fromProperty->getValue($this->builder));
        $this->assertSame(null, $this->joinProperty->getValue($this->builder));
        $this->assertSame(null, $this->whereProperty->getValue($this->builder));
        $this->assertSame(null, $this->groupProperty->getValue($this->builder));
        $this->assertSame(null, $this->havingProperty->getValue($this->builder));
        $this->assertSame(null, $this->orderProperty->getValue($this->builder));
        $this->assertSame(null, $this->valuesProperty->getValue($this->builder));
        $this->assertSame(null, $this->limitProperty->getValue($this->builder));
        $this->assertSame(null, $this->insertProperty->getValue($this->builder));
        $this->assertSame(null, $this->updateProperty->getValue($this->builder));
        $this->assertSame(null, $this->deleteProperty->getValue($this->builder));
        $this->assertSame([], $this->parametersProperty->getValue($this->builder));
        $this->assertSame(null, $this->queryTypeProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function select () {
        $this->selectInitMock();

        $this->assertSame($this->builder, $this->builder->select('column1'));
        $this->assertSame('selectModel', $this->selectProperty->getValue($this->builder));
        $this->assertSame('joinModel', $this->joinProperty->getValue($this->builder));
        $this->assertSame('whereModel', $this->whereProperty->getValue($this->builder));
        $this->assertSame('groupModel', $this->groupProperty->getValue($this->builder));
        $this->assertSame('havingModel', $this->havingProperty->getValue($this->builder));
        $this->assertSame('orderModel', $this->orderProperty->getValue($this->builder));
        $this->assertSame('limitModel', $this->limitProperty->getValue($this->builder));
        $this->assertSame(QueryBuilder::SELECT, $this->queryTypeProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function addSelect_withSelect () {
        $mockSelect = $this->getMockBuilder(SelectModel::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $this->selectProperty->setValue($this->builder, $mockSelect);
        $mockSelect->expects($this->once())
                   ->method('add')
                   ->with(['column1']);

        $this->mockFactory->expects($this->never())
                          ->method('createSelectModel');

        $this->assertSame($this->builder, $this->builder->addSelect('column1'));
    }

    /**
     * @test
     */
    public function addSelect () {
        $this->selectInitMock();
        $this->assertSame($this->builder, $this->builder->addSelect('column1'));

        $mockSelect = $this->getMockBuilder(SelectModel::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $this->selectProperty->setValue($this->builder, $mockSelect);

        $mockSelect->expects($this->once())
                   ->method('add')
                   ->with(['column2']);
        $this->assertSame($this->builder, $this->builder->addSelect('column2'));
    }

    /**
     * @test
     */
    public function insert () {
        $this->mockFactory->expects($this->once())
                          ->method('createInsertModel')
                          ->with('example1')
                          ->will($this->returnValue('insertModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createValuesModel')
                          ->will($this->returnValue('valuesModel'));

        $this->assertSame($this->builder, $this->builder->insert('example1'));
        $this->assertSame('insertModel', $this->insertProperty->getValue($this->builder));
        $this->assertSame('valuesModel', $this->valuesProperty->getValue($this->builder));
        $this->assertSame(QueryBuilder::INSERT, $this->queryTypeProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function update () {
        $this->mockFactory->expects($this->once())
                          ->method('createUpdateModel')
                          ->with('example1')
                          ->will($this->returnValue('updateModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createValuesModel')
                          ->will($this->returnValue('valuesModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue('whereModel'));

        $this->assertSame($this->builder, $this->builder->update('example1'));
        $this->assertSame('updateModel', $this->updateProperty->getValue($this->builder));
        $this->assertSame('valuesModel', $this->valuesProperty->getValue($this->builder));
        $this->assertSame('whereModel', $this->whereProperty->getValue($this->builder));
        $this->assertSame(QueryBuilder::UPDATE, $this->queryTypeProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function delete () {
        $this->mockFactory->expects($this->once())
                          ->method('createDeleteModel')
                          ->with('example1')
                          ->will($this->returnValue('deleteModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue('whereModel'));

        $this->assertSame($this->builder, $this->builder->delete('example1'));
        $this->assertSame('deleteModel', $this->deleteProperty->getValue($this->builder));
        $this->assertSame('whereModel', $this->whereProperty->getValue($this->builder));
        $this->assertSame(QueryBuilder::DELETE, $this->queryTypeProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function from () {
        $this->mockFactory->expects($this->once())
                          ->method('createFromModel')
                          ->with('example1', 'e1')
                          ->will($this->returnValue('fromModel'));
        $this->assertSame($this->builder, $this->builder->from('example1', 'e1'));
        $this->assertSame('fromModel', $this->fromProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function join () {
        $joinModelMock = $this->getMockBuilder(JoinModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createJoinModel')
                          ->will($this->returnValue($joinModelMock));

        $joinModelMock->expects($this->at(0))
                      ->method('add')
                      ->with('example1', 'e1', 'WITH', 'e1.id = 1', 'INNER');
        $joinModelMock->expects($this->at(1))
                      ->method('add')
                      ->with('example2', 'e2', 'WITH', 'e2.id = 1', 'LEFT');

        $this->assertSame($this->builder, $this->builder->join('example1', 'e1', 'WITH', 'e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->join('example2', 'e2', 'WITH', 'e2.id = 1', 'LEFT'));
    }

    /**
     * @test
     */
    public function leftJoin () {
        $joinModelMock = $this->getMockBuilder(JoinModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createJoinModel')
                          ->will($this->returnValue($joinModelMock));

        $joinModelMock->expects($this->at(0))
                      ->method('add')
                      ->with('example1', 'e1', 'WITH', 'e1.id = 1', 'LEFT');
        $joinModelMock->expects($this->at(1))
                      ->method('add')
                      ->with('example2', 'e2', 'WITH', 'e2.id = 1', 'LEFT');

        $this->assertSame($this->builder, $this->builder->leftJoin('example1', 'e1', 'WITH', 'e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->leftJoin('example2', 'e2', 'WITH', 'e2.id = 1'));
    }

    /**
     * @test
     */
    public function innerJoin () {
        $joinModelMock = $this->getMockBuilder(JoinModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createJoinModel')
                          ->will($this->returnValue($joinModelMock));

        $joinModelMock->expects($this->at(0))
                      ->method('add')
                      ->with('example1', 'e1', 'WITH', 'e1.id = 1', 'INNER');
        $joinModelMock->expects($this->at(1))
                      ->method('add')
                      ->with('example2', 'e2', 'WITH', 'e2.id = 1', 'INNER');

        $this->assertSame($this->builder, $this->builder->innerJoin('example1', 'e1', 'WITH', 'e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->innerJoin('example2', 'e2', 'WITH', 'e2.id = 1'));
    }

    /**
     * @test
     */
    public function where () {
        $whereModelMock = $this->getMockBuilder(WhereModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue($whereModelMock));

        $whereModelMock->expects($this->once())
                       ->method('set')
                       ->with(['e1.id = 1']);

        $this->assertSame($this->builder, $this->builder->where('e1.id = 1'));
    }

    /**
     * @test
     */
    public function andWhere () {
        $whereModelMock = $this->getMockBuilder(WhereModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue($whereModelMock));

        $whereModelMock->expects($this->at(0))
                       ->method('count')
                       ->will($this->returnValue(0));

        $whereModelMock->expects($this->at(1))
                       ->method('add')
                       ->with('e1.id = 1');

        $whereModelMock->expects($this->at(2))
                       ->method('count')
                       ->will($this->returnValue(1));
        $whereModelMock->expects($this->at(3))
                       ->method('add')
                       ->with('AND e2.id = 2');

        $this->assertSame($this->builder, $this->builder->andWhere('e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->andWhere('e2.id = 2'));
    }

    /**
     * @test
     */
    public function orWhere () {
        $whereModelMock = $this->getMockBuilder(WhereModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue($whereModelMock));

        $whereModelMock->expects($this->at(0))
                       ->method('count')
                       ->will($this->returnValue(0));

        $whereModelMock->expects($this->at(1))
                       ->method('add')
                       ->with('e1.id = 1');

        $whereModelMock->expects($this->at(2))
                       ->method('count')
                       ->will($this->returnValue(1));
        $whereModelMock->expects($this->at(3))
                       ->method('add')
                       ->with('OR e2.id = 2');

        $this->assertSame($this->builder, $this->builder->orWhere('e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->orWhere('e2.id = 2'));
    }

    /**
     * @test
     */
    public function groupBy () {
        $groupModelMock = $this->getMockBuilder(GroupModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->mockFactory->expects($this->once())
                          ->method('createGroupModel')
                          ->will($this->returnValue($groupModelMock));

        $groupModelMock->expects($this->once())
                       ->method('set')
                       ->with(['e1.id']);

        $this->assertSame($this->builder, $this->builder->groupBy('e1.id'));
    }

    /**
     * @test
     */
    public function addGroupBy () {
        $groupModelMock = $this->getMockBuilder(GroupModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->mockFactory->expects($this->once())
                          ->method('createGroupModel')
                          ->will($this->returnValue($groupModelMock));

        $groupModelMock->expects($this->at(0))
                       ->method('add')
                       ->with('e1.id');
        $groupModelMock->expects($this->at(1))
                       ->method('add')
                       ->with('e2.id');

        $this->assertSame($this->builder, $this->builder->addGroupBy('e1.id'));
        $this->assertSame($this->builder, $this->builder->addGroupBy('e2.id'));
    }

    /**
     * @test
     */
    public function having () {
        $havingModelMock = $this->getMockBuilder(HavingModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createHavingModel')
                          ->will($this->returnValue($havingModelMock));

        $havingModelMock->expects($this->once())
                        ->method('set')
                        ->with(['e1.id = 1']);

        $this->assertSame($this->builder, $this->builder->having('e1.id = 1'));
    }

    /**
     * @test
     */
    public function andHaving () {
        $havingModelMock = $this->getMockBuilder(HavingModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createHavingModel')
                          ->will($this->returnValue($havingModelMock));

        $havingModelMock->expects($this->at(0))
                        ->method('add')
                        ->with('AND e1.id = 1');
        $havingModelMock->expects($this->at(1))
                        ->method('add')
                        ->with('AND e2.id = 2');

        $this->assertSame($this->builder, $this->builder->andHaving('e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->andHaving('e2.id = 2'));
    }

    /**
     * @test
     */
    public function orHaving () {
        $havingModelMock = $this->getMockBuilder(HavingModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createHavingModel')
                          ->will($this->returnValue($havingModelMock));

        $havingModelMock->expects($this->at(0))
                        ->method('add')
                        ->with('OR e1.id = 1');
        $havingModelMock->expects($this->at(1))
                        ->method('add')
                        ->with('OR e2.id = 2');

        $this->assertSame($this->builder, $this->builder->orHaving('e1.id = 1'));
        $this->assertSame($this->builder, $this->builder->orHaving('e2.id = 2'));
    }

    /**
     * @test
     */
    public function orderBy () {
        $orderModelMock = $this->getMockBuilder(OrderModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createOrderModel')
                          ->will($this->returnValue($orderModelMock));

        $orderModelMock->expects($this->once())
                       ->method('add')
                       ->with(['e1.id'], 'ASC');

        $this->assertSame($this->builder, $this->builder->orderBy('e1.id'));
    }

    /**
     * @test
     */
    public function addOrderBy () {
        $orderModelMock = $this->getMockBuilder(OrderModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createOrderModel')
                          ->will($this->returnValue($orderModelMock));

        $orderModelMock->expects($this->at(0))
                       ->method('add')
                       ->with(['e1.id'], 'ASC');
        $orderModelMock->expects($this->at(1))
                       ->method('add')
                       ->with(['e2.id', 'e2.info'], 'DESC');

        $this->assertSame($this->builder, $this->builder->addOrderBy('e1.id'));
        $this->assertSame($this->builder, $this->builder->addOrderBy(['e2.id', 'e2.info'], 'DESC'));
    }

    /**
     * @test
     */
    public function setFirstResult () {
        $limitModelMock = $this->getMockBuilder(LimitModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createLimitModel')
                          ->will($this->returnValue($limitModelMock));
        $limitModelMock->expects($this->once())
                       ->method('setOffset')
                       ->with(2);

        $this->assertSame($this->builder, $this->builder->setFirstResult(2));
    }

    /**
     * @test
     */
    public function setMaxResult () {
        $limitModelMock = $this->getMockBuilder(LimitModel::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->mockFactory->expects($this->once())
                          ->method('createLimitModel')
                          ->will($this->returnValue($limitModelMock));
        $limitModelMock->expects($this->once())
                       ->method('setLimit')
                       ->with(2);

        $this->assertSame($this->builder, $this->builder->setMaxResult(2));
    }

    /**
     * @test
     */
    public function set () {
        $valuesModelMock = $this->getMockBuilder(ValuesModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->mockFactory->expects($this->once())
                          ->method('createValuesModel')
                          ->will($this->returnValue($valuesModelMock));

        $valuesModelMock->expects($this->once())
                        ->method('set')
                        ->with(['param1' => ':param1', 'param2' => ':param2']);

        $this->assertSame($this->builder, $this->builder->set(['param1' => 'value1', 'param2' => 'value2']));
        $this->assertSame(['param1' => 'value1', 'param2' => 'value2'],
                          $this->parametersProperty->getValue($this->builder));
    }

    /**
     * @test
     */
    public function toStringMethod_select () {
        $this->queryTypeProperty->setValue($this->builder, QueryBuilder::SELECT);
        $this->selectProperty->setValue($this->builder, 'SELECT ');
        $this->fromProperty->setValue($this->builder, 'FROM ');
        $this->joinProperty->setValue($this->builder, 'JOIN ');
        $this->whereProperty->setValue($this->builder, 'WHERE ');
        $this->groupProperty->setValue($this->builder, 'GROUP ');
        $this->havingProperty->setValue($this->builder, 'HAVING ');
        $this->orderProperty->setValue($this->builder, 'ORDER');

        $this->assertSame('SELECT FROM JOIN WHERE GROUP HAVING ORDER', $this->builder->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod_insert () {
        $this->queryTypeProperty->setValue($this->builder, QueryBuilder::INSERT);
        $this->insertProperty->setValue($this->builder, 'INSERT ');
        $this->valuesProperty->setValue($this->builder, 'VALUES');

        $this->assertSame('INSERT VALUES', $this->builder->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod_delete () {
        $this->queryTypeProperty->setValue($this->builder, QueryBuilder::DELETE);
        $this->deleteProperty->setValue($this->builder, 'DELETE ');
        $this->whereProperty->setValue($this->builder, 'WHERE');

        $this->assertSame('DELETE WHERE', $this->builder->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod_update () {
        $valuesModelMock = $this->getMockBuilder(ValuesModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();
        $valuesModelMock->expects($this->once())
                        ->method('update')
                        ->will($this->returnValue('VALUES '));
        $this->queryTypeProperty->setValue($this->builder, QueryBuilder::UPDATE);
        $this->updateProperty->setValue($this->builder, 'UPDATE ');
        $this->valuesProperty->setValue($this->builder, $valuesModelMock);
        $this->whereProperty->setValue($this->builder, 'WHERE');

        $this->assertSame('UPDATE VALUES WHERE', $this->builder->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod_invalid () {
        $this->queryTypeProperty->setValue($this->builder, 'invalid');
        $this->setExpectedExceptionRegExp(QueryBuilderException::class);
        $this->builder->__toString();
    }

    private function selectInitMock () {
        $this->mockFactory->expects($this->once())
                          ->method('createSelectModel')
                          ->with(['column1'])
                          ->will($this->returnValue('selectModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createJoinModel')
                          ->will($this->returnValue('joinModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createWhereModel')
                          ->will($this->returnValue('whereModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createGroupModel')
                          ->will($this->returnValue('groupModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createHavingModel')
                          ->will($this->returnValue('havingModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createOrderModel')
                          ->will($this->returnValue('orderModel'));
        $this->mockFactory->expects($this->once())
                          ->method('createLimitModel')
                          ->will($this->returnValue('limitModel'));
    }
}