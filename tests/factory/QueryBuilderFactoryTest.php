<?php
use database\DriverBundle\connection\interfaces\ConnectionInterface;
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

    protected function setUp () {
        $this->mockConnection = $this->getMockBuilder(ConnectionInterface::class)
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $this->factory = new QueryBuilderFactory($this->mockConnection);
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