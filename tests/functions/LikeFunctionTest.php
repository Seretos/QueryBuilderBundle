<?php
use database\QueryBuilderBundle\functions\LikeFunction;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 22.05.2016
 * Time: 02:16
 */
class LikeFunctionTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function toStringMethod () {
        $model = new LikeFunction('', '');
        $this->assertSame(' LIKE ', $model->__toString());
        $model = new LikeFunction('e1.info', ':param1');
        $this->assertSame('e1.info LIKE :param1', $model->__toString());
    }
}