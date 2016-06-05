<?php
use database\DriverBundle\connection\mysqli\MysqliConnection;
use database\QueryBuilderBundle\factory\QueryBuilderBundleFactory;
use database\QueryBuilderBundle\tests\AbstractFunctionalQueryBuilderTest;
use database\QueryBundle\factory\QueryBundleFactory;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 18.05.2016
 * Time: 21:34
 */
class MysqliFunctionalQueryBuilderTest extends AbstractFunctionalQueryBuilderTest {
    protected function setUp () {
        parent::setUp();
        $mysqli = new mysqli(self::CONFIG['host'],
                             self::CONFIG['user'],
                             self::CONFIG['password'],
                             self::CONFIG['database']);

        $this->connection = new MysqliConnection($mysqli);

        $this->queryFactory = new QueryBundleFactory($this->connection);
    }
}