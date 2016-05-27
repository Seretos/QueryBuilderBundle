<?php
use database\QueryBuilderBundle\model\WhereModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:09
 */
class WhereModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var WhereModel
     */
    private $model;

    protected function setUp () {
        $this->model = new WhereModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->model->__toString());
        $this->model->set(['column1 = :col1', 'AND column2 <> :col2']);
        $this->assertSame('WHERE column1 = :col1 AND column2 <> :col2 ', $this->model->__toString());
        $this->model->add('OR column3 = :col3');
        $this->assertSame('WHERE column1 = :col1 AND column2 <> :col2 OR column3 = :col3 ',
                          $this->model->__toString());
    }
}