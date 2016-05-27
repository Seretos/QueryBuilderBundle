<?php
use database\QueryBuilderBundle\model\SelectModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:01
 */
class SelectModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var SelectModel
     */
    private $model;

    protected function setUp () {
        $this->model = new SelectModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('SELECT * ', $this->model->__toString());
        $this->model->set(['column1', 'column2']);
        $this->assertSame('SELECT column1,column2 ', $this->model->__toString());
        $this->model->add(['column3']);
        $this->assertSame('SELECT column1,column2,column3 ', $this->model->__toString());
    }
}