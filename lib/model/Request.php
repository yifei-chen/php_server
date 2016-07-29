<?php

/**
 * Created by PhpStorm.
 * User: yichen
 * Date: 7/29/16
 * Time: 11:21
 */
class Request
{
    private $params = null;
    private $query = null;

    public function __construct($params,$query)
    {
        $this->params = $params;
        $this->query = $query;
    }

    /**
     * @return null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return null
     */
    public function getQuery()
    {
        return $this->query;
    }


}