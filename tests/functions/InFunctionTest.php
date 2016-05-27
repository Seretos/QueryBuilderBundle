<?php
use database\QueryBuilderBundle\functions\InFunction;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:13
 */
class InFunctionTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function toStringMethod () {
        $model = new InFunction('', '');
        $this->assertSame(' IN()', $model->__toString());
        $model = new InFunction('e1.id', '1,2,3');
        $this->assertSame('e1.id IN(1,2,3)', $model->__toString());
    }
}