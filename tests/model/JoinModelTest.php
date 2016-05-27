<?php
use database\QueryBuilderBundle\model\JoinModel;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 01:54
 */
class JoinModelTest extends PHPUnit_Framework_TestCase {
    /**
     * @var JoinModel
     */
    private $model;

    protected function setUp () {
        $this->model = new JoinModel();
    }

    /**
     * @test
     */
    public function toStringMethod () {
        $this->assertSame('', $this->model->__toString());
        $this->model->add('example1', 'e1', 'WITH', 'e1.id = 1');
        $this->assertSame('INNER JOIN example1 AS e1 ON e1.id = 1 ', $this->model->__toString());
        $this->model->add('example2', 'e2', 'ON', 'e2.id = 2', 'LEFT');
        $this->assertSame('INNER JOIN example1 AS e1 ON e1.id = 1 LEFT JOIN example2 AS e2 ON e2.id = 2 ',
                          $this->model->__toString());
    }
}