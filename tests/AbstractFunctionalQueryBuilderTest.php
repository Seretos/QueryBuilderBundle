<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 18.05.2016
 * Time: 21:33
 */

namespace database\QueryBuilderBundle\tests;


use database\DriverBundle\tests\AbstractFunctionalDatabaseTest;
use database\QueryBuilderBundle\builder\ExpressionBuilder;
use database\QueryBuilderBundle\builder\QueryBuilder;
use database\QueryBuilderBundle\factory\QueryBuilderBundleFactory;
use database\QueryBundle\factory\QueryBundleFactory;

abstract class AbstractFunctionalQueryBuilderTest extends AbstractFunctionalDatabaseTest {
    /**
     * @var QueryBundleFactory
     */
    protected $queryFactory;

    /**
     * @var QueryBuilderBundleFactory
     */
    protected $queryBuilderFactory;

    /**
     * @var ExpressionBuilder
     */
    private $expression;

    protected function setUp () {
        parent::setUp();
        $this->pdo->exec('CREATE TABLE example3(
                                id INT AUTO_INCREMENT
                                ,info VARCHAR(255)
                                , example1_id INT
                                , example2_id INT

                                , PRIMARY KEY(id)
                                , FOREIGN KEY(example1_id) REFERENCES example1(id)
                                , FOREIGN KEY(example2_id) REFERENCES example2(id)
                          );');

        $this->pdo->exec('CREATE TABLE example4(
                                id INT AUTO_INCREMENT
                                ,info VARCHAR(255)
                                , example3_id INT

                                , PRIMARY KEY(id)
                                , FOREIGN KEY(example3_id) REFERENCES example3(id)
                          );');

        $this->pdo->exec('INSERT INTO example3(info,example1_id,example2_id) VALUES(\'test1\',1,1)');
        $this->pdo->exec('INSERT INTO example3(info,example2_id) VALUES(\'test2\',2)');
        $this->pdo->exec('INSERT INTO example3(info,example1_id) VALUES(\'test3\',3)');

        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test1\',1)');
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test1\',1)');

        $this->queryBuilderFactory = new QueryBuilderBundleFactory();
        $this->expression = new ExpressionBuilder();
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder () {
        $builder = $this->queryBuilderFactory->createQueryBuilder();

        return $builder;
    }

    protected function expr () {
        return $this->expression;
    }

    /**
     * @test
     */
    public function simpleSelect () {
        $queryBuilder = $this->createQueryBuilder()
                             ->select(['e1.info'])
                             ->from('example1', 'e1');

        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT e1.info FROM example1 e1 ', $query->getSql());
        $result = $query->buildResult();
        for ($i = 0; $i < 50; $i++) {
            $this->assertSame(['info' => 'test'.$i], $result->current());
            $result->next();
        }
    }

    /**
     * @test
     */
    public function joinSelect () {
        $queryBuilder = $this->createQueryBuilder()
                             ->select('e3.id, e3.info AS info3,e1.info AS info1')
                             ->from('example3', 'e3')
                             ->innerJoin('example2', 'e2', 'WITH', 'e2.id = e3.example2_id')
                             ->leftJoin('example1', 'e1', 'WITH', 'e1.id = e3.example1_id')
                             ->join('example4', 'e4', 'WITH', 'e4.example3_id = e3.id', 'LEFT OUTER');

        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT e3.id, e3.info AS info3,e1.info AS info1 FROM example3 e3 INNER JOIN example2 AS e2 ON e2.id = e3.example2_id LEFT JOIN example1 AS e1 ON e1.id = e3.example1_id LEFT OUTER JOIN example4 AS e4 ON e4.example3_id = e3.id ',
                          $query->getSql());
        $result = $query->buildResult();
        $this->assertEquals([['id' => 1, 'info3' => 'test1', 'info1' => 'test0'],
                             ['id' => 1, 'info3' => 'test1', 'info1' => 'test0'],
                             ['id' => 2, 'info3' => 'test2', 'info1' => null]],
                            iterator_to_array($result));
    }

    /**
     * @test
     */
    public function whereSelect () {
        $queryBuilder = $this->createQueryBuilder()
                             ->select(['e1.info'])
                             ->from('example1', 'e1')
                             ->where('e1.info = :info1')
                             ->andWhere($this->expr()
                                             ->like('e1.info', ':info2'))
                             ->orWhere($this->expr()
                                            ->andX('e1.id = 3', 'e1.info <> :info1'));

        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $query->setParameter('info1', 'test1');
        $query->setParameter('info2', 'test2');
        $this->assertSame('SELECT e1.info FROM example1 e1 WHERE e1.info = :info1 AND e1.info LIKE :info2 OR (e1.id = 3 AND e1.info <> :info1 ) ',
                          $query->getSql());
        $result = $query->buildResult();
        $this->assertSame([['info' => 'test2']], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function groupBySelect () {
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test2\',1)');
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test2\',1)');

        $queryBuilder = $this->createQueryBuilder()
                             ->select('e4.info')
                             ->from('example4', 'e4')
                             ->groupBy('e4.info');
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT e4.info FROM example4 e4 GROUP BY e4.info ', $query->getSql());
        $result = $query->buildResult();
        $this->assertSame([['info' => 'test1'], ['info' => 'test2']], iterator_to_array($result));
    }

    /**
     * @test
     */
    public function havingSelect () {
        $queryBuilder = $this->createQueryBuilder()
                             ->select('e1.info,e1.id')
                             ->from('example1', 'e1')
                             ->groupBy(['e1.info', 'e1.id'])
                             ->having('id < 10')
                             ->andHaving('info = :info1')
                             ->orHaving('info = :info2');
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $query->setParameter('info1', 'test1');
        $query->setParameter('info2', 'test2');
        $this->assertSame('SELECT e1.info,e1.id FROM example1 e1 GROUP BY e1.info,e1.id HAVING id < 10 AND info = :info1 OR info = :info2 ',
                          $query->getSql());
        $result = $query->buildResult();
        $this->assertEquals([['info' => 'test1', 'id' => '2'], ['info' => 'test2', 'id' => '3']],
                            iterator_to_array($result));
    }

    /**
     * @test
     */
    public function orderBySelect () {
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test2\',1)');
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test2\',1)');

        $queryBuilder = $this->createQueryBuilder()
                             ->select('e4.info,e4.id')
                             ->from('example4', 'e4')
                             ->orderBy('e4.info', 'DESC')
                             ->addOrderBy(['e4.id'], 'ASC');

        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT e4.info,e4.id FROM example4 e4 ORDER BY e4.info DESC ,e4.id ASC ', $query->getSql());
        $result = $query->buildResult();
        $this->assertEquals([
                                0 => ['info' => 'test2', 'id' => '3'],
                                1 => ['info' => 'test2', 'id' => '4'],
                                2 => ['info' => 'test1', 'id' => '1'],
                                3 => ['info' => 'test1', 'id' => '2']
                            ],
                            iterator_to_array($result));
    }

    /**
     * @test
     */
    public function limitSelect () {
        $queryBuilder = $this->createQueryBuilder()
                             ->select('*')
                             ->from('example1', 'e1')
                             ->setFirstResult(2)
                             ->setMaxResult(3);
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT * FROM example1 e1 LIMIT 2,3 ', $query->getSql());
        $result = $query->buildResult();
        $this->assertEquals([
                                0 => ['id' => '3', 'info' => 'test2',],
                                1 => ['id' => '4', 'info' => 'test3',],
                                2 => ['id' => '5', 'info' => 'test4',],
                            ],
                            iterator_to_array($result));
    }

    /**
     * @test
     */
    public function subSelect () {
        $example3Builder = $this->createQueryBuilder()
                                ->select('e3.example1_id')
                                ->from('example3', 'e3');

        $queryBuilder = $this->createQueryBuilder()
                             ->select('*')
                             ->from('example1', 'e1')
                             ->where($this->expr()
                                          ->in('e1.id', $example3Builder));
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('SELECT * FROM example1 e1 WHERE e1.id IN(SELECT e3.example1_id FROM example3 e3 ) ',
                          $query->getSql());
        $result = $query->buildResult();
        $this->assertEquals([
                                0 => ['id' => '1', 'info' => 'test0',],
                                1 => ['id' => '3', 'info' => 'test2',]
                            ],
                            iterator_to_array($result));
    }

    /**
     * @test
     */
    public function insert () {
        $queryBuilder = $this->createQueryBuilder()
                             ->insert('example1')
                             ->set(['info' => 'inserted']);
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('INSERT INTO example1 (info) VALUES(:info) ', $query->getSql());
        $result = $query->buildResult();
        $this->assertSame(1, $result->rowCount());

        $secondBuilder = $this->createQueryBuilder()
                              ->select('e1.id,e1.info')
                              ->from('example1', 'e1')
                              ->where('info = :info');
        $secondQuery = $this->queryFactory->createQuery($secondBuilder->__toString(), $secondBuilder->getParameters());
        $validate = $secondQuery->setParameter('info', 'inserted')
                                ->buildResult();
        $this->assertEquals([['id' => '51', 'info' => 'inserted']], iterator_to_array($validate));
    }

    /**
     * @test
     */
    public function update () {
        $queryBuilder = $this->createQueryBuilder()
                             ->update('example1')
                             ->set(['info' => 'updated'])
                             ->where('id < 4');
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('UPDATE example1 SET info = :info WHERE id < 4 ', $query->getSql());
        $result = $query->buildResult();
        $this->assertSame(3, $result->rowCount());

        $secondBuilder = $this->createQueryBuilder()
                              ->select('e1.id,e1.info')
                              ->from('example1', 'e1')
                              ->where('e1.id < 5');
        $secondQuery = $this->queryFactory->createQuery($secondBuilder->__toString(), $secondBuilder->getParameters());
        $validate = $secondQuery->buildResult();
        $this->assertEquals([['id' => '1', 'info' => 'updated'],
                             ['id' => '2', 'info' => 'updated'],
                             ['id' => '3', 'info' => 'updated'],
                             ['id' => '4', 'info' => 'test3']],
                            iterator_to_array($validate));
    }

    /**
     * @test
     */
    public function delete () {
        $this->pdo->exec('INSERT INTO example4(info,example3_id) VALUES(\'test2\',1)');

        $queryBuilder = $this->createQueryBuilder()
                             ->delete('example4')
                             ->where('id < 3');
        $query = $this->queryFactory->createQuery($queryBuilder->__toString(), $queryBuilder->getParameters());
        $this->assertSame('DELETE FROM example4 WHERE id < 3 ', $query->getSql());
        $result = $query->buildResult();
        $this->assertSame(2, $result->rowCount());

        $secondBuilder = $this->createQueryBuilder()
                              ->select('e4.id,e4.info')
                              ->from('example4', 'e4')
                              ->where('e4.id < 4');
        $secondQuery = $this->queryFactory->createQuery($secondBuilder->__toString(), $secondBuilder->getParameters());
        $validate = $secondQuery->buildResult();
        $this->assertEquals([['id' => '3', 'info' => 'test2']],
                            iterator_to_array($validate));
    }
}