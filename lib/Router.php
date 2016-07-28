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
        array_shift($array);
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



    public function run($method,$route){
        switch($method){
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
        $uri = $route;
        if(!$uri){
            return 'bad request';
        }
        $params = array();
        $uriArray = $this->uriToArray($uri);
        $routes = array_keys($this->executeArray);
        $matchRoute = $this->getRoute($routes,$uriArray,$params);
        $func = $this->executeArray[$matchRoute];
        $res = $func?call_user_func_array($func, array($params)):null;
        if(!$res){
            return 'route not defined';
        }
        $result = null;
        if(is_array($res)){
            $result = json_encode($res);
        }
        return $result;
    }

    private function getRoute($routes,$uriArray,&$params){
        $pattern = '/^:/';
        foreach($routes as $route){
            $routeArray = $this->uriToArray($route);
            $matchFlag = true;
            if(count($routeArray)!=count($uriArray)){
                continue;
            }
            for($i=0; $i<count($uriArray); $i++){
                if(preg_match($pattern,$routeArray[$i])){
                    $param = explode(':',$routeArray[$i])[1];
                    $params[$param] = $uriArray[$i];
                    continue;
                }
                if($routeArray[$i]!=$uriArray[$i]){
                    $matchFlag = false;
                    $params = array();
                    break;
                }
            }
            if($matchFlag){
                return $route;
            }
        }
    }



}