<?php
use database\QueryBuilderBundle\model\LimitModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:57
 */
class LimitModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var LimitModel
     */
    private $model;

    protected function setUp () {
        $this->model = new LimitModel();
    }

    /**
     * @test
     */
    public function toStringMethod_offset () {
        $this->assertSame('', $this->model->__toString());
        $this->model->setOffset(44);
        $this->assertSame('LIMIT 44,100 ', $this->model->__toString());
        $this->model->setLimit(12);
        $this->assertSame('LIMIT 44,12 ', $this->model->__toString());
    }

    /**
     * @test
     */
    public function toStringMethod_limit () {
        $this->assertSame('', $this->model->__toString());
        $this->model->setLimit(1);
        $this->assertSame('LIMIT 0,1 ', $this->model->__toString());
        $this->model->setLimit(44);
        $this->assertSame('LIMIT 0,44 ', $this->model->__toString());
        $this->model->setOffset(12);
        $this->assertSame('LIMIT 12,44 ', $this->model->__toString());
    }
}