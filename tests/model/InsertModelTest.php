<?php
use database\QueryBuilderBundle\model\InsertModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:52
 */
class InsertModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var InsertModel
     */
    private $model;

    protected function setUp () {
        $this->model = new InsertModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('INSERT INTO  ', $this->model->__toString());
        $this->model->setTable('example1');
        $this->assertSame('INSERT INTO example1 ', $this->model->__toString());
    }
}