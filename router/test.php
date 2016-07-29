<?php

/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午9:09
 */
require '../lib/model/Request.php';

class test
{
    private static $_instance = null;

    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get(){
        return array('message'=>'success');
    }

    public function param($res){
        return array($res->getParams(),$res->getQuery());
    }
}