<?php
use database\QueryBuilderBundle\model\OrderModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:59
 */
class OrderModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var OrderModel
     */
    private $model;

    protected function setUp () {
        $this->model = new OrderModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->model->__toString());
        $this->model->add(['column1'], 'ASC');
        $this->assertSame('ORDER BY column1 ASC ', $this->model->__toString());
        $this->model->add(['column2', 'column3'], 'DESC');
        $this->assertSame('ORDER BY column1 ASC ,column2,column3 DESC ', $this->model->__toString());
    }
}