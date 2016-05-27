<?php
use database\QueryBuilderBundle\model\GroupModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:10
 */
class GroupModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var GroupModel
     */
    private $model;

    protected function setUp () {
        $this->model = new GroupModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->model->__toString());
        $this->model->set(['column1', 'column2']);
        $this->assertSame('GROUP BY column1,column2 ', $this->model->__toString());
        $this->model->add('column3');
        $this->assertSame('GROUP BY column1,column2,column3 ', $this->model->__toString());
    }
}