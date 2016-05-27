<?php
use database\QueryBuilderBundle\model\UpdateModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:04
 */
class UpdateModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var UpdateModel
     */
    private $model;

    protected function setUp () {
        $this->model = new UpdateModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('UPDATE  SET ', $this->model->__toString());
        $this->model->setTable('example1');
        $this->assertSame('UPDATE example1 SET ', $this->model->__toString());
    }
}