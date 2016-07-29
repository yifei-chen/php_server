<?php

/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午8:25
 */

require_once 'model/Request.php';

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

    private function queryString2Array($string){
        if($string==""){
            return array();
        }
        $queryArray = array();
        $keyValues = explode("&",$string);
        foreach ($keyValues as $query){
            $queryPair = explode("=",$query);
            $queryArray[$queryPair[0]]=count($queryPair)>1?$queryPair[1]:"";
        }
        return $queryArray;
    }

    public function run($method,$unionRoute){
        echo 'request received : '.$unionRoute.' method : '.$method."\n";
        $unionRouteArray = explode("?",$unionRoute);
        $route = $unionRouteArray[0];
        $query = count($unionRouteArray)>1?$unionRouteArray[1]:"";
        $queryArray = $this->queryString2Array($query);
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
        $res = $func?call_user_func_array($func, array(new Request($params,$queryArray))):null;
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