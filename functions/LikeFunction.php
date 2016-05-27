<?php
/**
 * Created by PhpStorm.
 * User: Seredos
 * Date: 05.05.2016
 * Time: 22:39
 */

namespace database\QueryBuilderBundle\functions;


class LikeFunction {
    private $source;
    private $target;

    /**
     * LikeFunction constructor.
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
        $result = $this->source.' LIKE '.$this->target;

        return $result;
    }
}