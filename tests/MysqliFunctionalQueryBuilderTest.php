<?php
use database\DriverBundle\connection\mysqli\MysqliConnection;
use database\QueryBuilderBundle\factory\QueryBuilderFactory;
use database\QueryBuilderBundle\tests\AbstractFunctionalQueryBuilderTest;

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

        $this->queryBuilderFactory = new QueryBuilderFactory($this->connection);
    }
}