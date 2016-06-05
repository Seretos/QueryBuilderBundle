<?php
use database\DriverBundle\connection\pdo\PdoConnection;
use database\QueryBuilderBundle\tests\AbstractFunctionalQueryBuilderTest;
use database\QueryBundle\factory\QueryBundleFactory;

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

        $this->queryFactory = new QueryBundleFactory($this->connection);
    }
}