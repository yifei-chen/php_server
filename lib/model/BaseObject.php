<?php

/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午10:03
 */
class BaseObject
{
    private static $_instance = null;

    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}