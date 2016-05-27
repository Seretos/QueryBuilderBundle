<?php
use database\QueryBuilderBundle\model\FromModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:08
 */
class FromModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var FromModel
     */
    private $model;

    protected function setUp () {
        $this->model = new FromModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('FROM   ', $this->model->__toString());
        $this->model->set('example1', 'e1');
        $this->assertSame('FROM example1 e1 ', $this->model->__toString());
    }
}