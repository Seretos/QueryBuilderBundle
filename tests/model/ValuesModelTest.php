<?php
use database\QueryBuilderBundle\model\ValuesModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:06
 */
class ValuesModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var ValuesModel
     */
    private $model;

    protected function setUp () {
        $this->model = new ValuesModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('() VALUES() ', $this->model->__toString());
        $this->model->set(['column1' => 'value1']);
        $this->assertSame('(column1) VALUES(value1) ', $this->model->__toString());
        $this->model->add('column2', 'value2');
        $this->assertSame('(column1,column2) VALUES(value1,value2) ', $this->model->__toString());
    }

    /**
     * @test
     */
    public function update () {
        $this->assertSame('', $this->model->update());
        $this->model->set(['column1' => 'value1']);
        $this->assertSame('column1 = value1 ', $this->model->update());
        $this->model->add('column2', 'value2');
        $this->assertSame('column1 = value1 ,column2 = value2 ', $this->model->update());
    }
}