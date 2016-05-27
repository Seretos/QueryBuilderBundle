<?php
use database\QueryBuilderBundle\model\DeleteModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:05
 */
class DeleteModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var DeleteModel
     */
    private $model;

    protected function setUp () {
        $this->model = new DeleteModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('DELETE FROM  ', $this->model->__toString());
        $this->model->setTable('example');
        $this->assertSame('DELETE FROM example ', $this->model->__toString());
    }
}