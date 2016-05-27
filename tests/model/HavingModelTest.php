<?php
use database\QueryBuilderBundle\model\HavingModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:50
 */
class HavingModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var HavingModel
     */
    private $model;

    protected function setUp () {
        $this->model = new HavingModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->model->__toString());
        $this->model->set(['column1 = :col1']);
        $this->assertSame('HAVING column1 = :col1 ', $this->model->__toString());
        $this->model->add('AND column2 <> :col2');
        $this->assertSame('HAVING column1 = :col1 AND column2 <> :col2 ', $this->model->__toString());
    }
}