<?php

/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午8:25
 */

class Router
{
    private static $_instance = null;

    private $get = array();
    private $post = array();
    private $executeArray = array();

    public function __construct()
    {

    }

    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function uriToArray($uri){
        $array = explode("/",$uri);
        return $array;
    }

    public function get($route,$class,$func){
        $this->get[$route] = array($class,$func);
        return $this;
    }

    public function post($route,$class,$func){
        $this->post[$route] = array($class,$func);
        return $this;
    }



    public function run(){
        switch($_SERVER['REQUEST_METHOD']){
            case 'GET':
                $this->executeArray = $this->get;
                break;
            case 'POST':
                $this->executeArray = $this->post;
                break;
            default:
                $this->executeArray = $this->get;
                break;
        }
        $uri = $_SERVER['REQUEST_URI'];
        if(!$uri){
            exit('bad request');
        }
        $uriArray = $this->uriToArray($uri);
        $func = $this->executeArray[$uri];
        $res = $func?call_user_func_array($func, array()):null;
        if(!$res){
            exit('route not defined');
        }
        $result = null;
        if(is_array($res)){
            $result = json_encode($res);
        }
        exit($result);
    }

    private function getRoute($routes,$uriArray){
        foreach($routes as $route){
            $routeArray = $this->uriToArray($route);
            $matchFlag = true;
            if(count($routeArray)!=count($uriArray)){
                continue;
            }
            for($i=0; $i<count($uriArray); $i++){
                if($routeArray[$i]!=$uriArray[$i]){
                    $matchFlag = false;
                    break;
                }
            }
            if($matchFlag){
                return $route;
            }
        }
    }



}