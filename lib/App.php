<?php

/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午10:48
 */
require_once 'Router.php';

class App
{
    private static $_instance = null;

    private  $router =null;

    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function run(){
        if($this->router){
            $this->router->run();
        }
    }

    /**
     * @param Router $router
     */
    public function setRoute(Router $router)
    {
        $this->router = $router;
    }


}