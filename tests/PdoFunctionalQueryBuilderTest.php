<?php
use database\DriverBundle\connection\pdo\PdoConnection;
use database\QueryBuilderBundle\factory\QueryBuilderFactory;
use database\QueryBuilderBundle\tests\AbstractFunctionalQueryBuilderTest;

/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 18.05.2016
 * Time: 21:34
 */
class PdoFunctionalQueryBuilderTest extends AbstractFunctionalQueryBuilderTest {
    protected function setUp () {
        parent::setUp();
        $this->connection = new PdoConnection(self::CONFIG['host'],
                                              self::CONFIG['user'],
                                              self::CONFIG['password'],
                                              self::CONFIG['database']);

        $this->queryBuilderFactory = new QueryBuilderFactory($this->connection);
    }
}