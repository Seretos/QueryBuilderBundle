QueryBuilderBundle
==================
this bundle provide an abstraction layer for mysql database queries

Installation
============
add the bundle in your composer.json as bellow:
```js
"require": {
    ...
    ,"Seretos/database/QueryBuilderBundle" : "v0.1.*"
    ,"Seretos/database/QueryBundle" : "v0.1.*"
    ,"Seretos/database/DriverBundle" : "v0.1.*"
},
"repositories" : [
    ...
    ,{
        "type" : "git",
        "url" : "https://github.com/Seretos/QueryBuilderBundle"
    }
    ,{
         "type" : "git",
         "url" : "https://github.com/Seretos/QueryBundle"
    }
    ,{
         "type" : "git",
         "url" : "https://github.com/Seretos/DriverBundle"
    }
]
```
and execute the composer update command

Usage
=====
create the query builder factory with your [driver bundle connection](https://github.com/Seretos/DriverBundle)
```php
$queryBuilderFactory = new QueryBuilderFactory($this->connection);
```
now create the query builder for your query
```php
$builder = new QueryBuilder($queryBuilderFactory);
```

## select
```php
$builder->select('e1.id') // $builder->select(); = SELECT *, $builder->select(['e1.col1','e1.col2']); = SELECT e1.col1,e1.col2
        ->from('example1','e1')
        ->innerJoin('example2','e2','ON','e1.example2_id = e2.id')
        ->where('e1.id IN(:ids)')
        ->andWhere('e2.col1 = :col1')
        ->orWhere('e2.col2 = :col2')
        ->groupBy(['e1.col1','e1.col2'])
        ->addGroupBy('e2.col1')
        ->having('e1.col2 BETWEEN :col2Min AND :col2Max')
        ->andHaving('e2.col3 = :col3')
        ->orHaving('e2.col4 = :col4')
        ->orderBy('e1.col1','ASC')
        ->addOrderBy(['e1.col2','e2.col1'],'DESC')
        ->setFirstResult(0)
        ->setMaxResult(50);
```

## insert / update
```php
if($id > 0){
    $builder->update('example1')
            ->where('id = :id');
}else{
    $builder->insert('example1');
}
$builder->set(['col1' => 'val1','col2' => 'val2']);
```

## delete
```php
$builder->delete('example1')
        ->where('id = :id');
```

# execute the query
```php
$query = $builder->buildQuery();
$query->setParameter('id',$id);
$result = $query->buildResult();
```

# expression builder
if you want to create complex query expressions, it is a better way to create the expression with the builder instead as string
```php
$expression = new Expression();
$andExpression = $expression->andX($expression->eq('col1',':col1'));
$orExpression = $expression->orX($expression->eq('col3',':col3')
                                ,$expression->like('col4',':col4'));

$andExpression->add($expression->like('col2',':col2');
$andExpression->add($orExpression);

$builder->andWhere($andExpression);
$andExpression->__toString(); // col1 = :col1 AND col2 LIKE :col2 AND (col3 = :col3 OR col4 = :col4)
```

Road map
========
the following features are not implemented but required for version 1.0

* functions:
    * GROUP_CONCAT
    * IF
* get the expression count (AbstractExpression)
* andWhere/orWhere without where
