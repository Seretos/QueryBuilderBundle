<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 05.05.2016
 * Time: 22:33
 */

namespace database\QueryBuilderBundle\functions;


class InFunction {
    /**
     * @var string
     */
    private $source;
    /**
     * @var string
     */
    private $target;

    /**
     * InFunction constructor.
     *
     * @param string $source
     * @param string $target
     */
    public function __construct ($source, $target) {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function __toString () {
        $result = $this->source.' IN('.$this->target.')';

        return $result;
    }
}