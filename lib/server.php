<?php

/**
 * Created by PhpStorm.
 * User: yichen
 * Date: 7/28/16
 * Time: 11:23
 */

require_once 'App.php';


class Server {
    private $ip;
    private $port;
    private $app;
    public function __construct($ip, $port,$app) {
        $this->ip = $ip;
        $this->port = $port;
        $this->app = $app;
        $this->await();
    }
    private function await() {
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($sock < 0) {
            echo "Error:" . socket_strerror(socket_last_error()) . "\n";
        }
        $ret = socket_bind($sock, $this->ip, $this->port);
        if (!$ret) {
            echo "BIND FAILED:" . socket_strerror(socket_last_error()) . "\n";
            exit;
        }
        echo "OK\n";
        $ret = socket_listen($sock);
        if ($ret < 0) {
            echo "LISTEN FAILED:" . socket_strerror(socket_last_error()) . "\n";
        }
        echo "Listen on ".$this->ip.":".$this->port."\n";
        do {
            $new_sock = null;
            try {
                $new_sock = socket_accept($sock);
            } catch (Exception $e) {
                echo $e->getMessage();
                echo "ACCEPT FAILED:" . socket_strerror(socket_last_error()) . "\n";
            }
            try {
                $request_string = socket_read($new_sock, 1024);
                $response = $this->output($request_string);
                socket_write($new_sock, $response);
                socket_close($new_sock);
            } catch (Exception $e) {
                echo $e->getMessage();
                echo "READ FAILED:" . socket_strerror(socket_last_error()) . "\n";
            }
        } while (TRUE);
    }

    private function output($request_string){
        $httpLine = explode("\r\n",$request_string)[0];
        $httpArray = explode(" ",$httpLine);
        $method = $httpArray[0];
        $route = $httpArray[1];
        $result = $this->app->run($method,$route);
        if($result){
            return $this->add_header($result);
        }else {
            return $this->not_found();
        }
    }

    private function not_found(){
        $content = "

<h1>File Not Found </h1>

";
        return "HTTP/1.1 404 File Not Found\r\nContent-Type: text/html\r\nContent-Length: ".strlen($content)."\r\n\r\n".$content;
    }
    /**
     * 加上头信息
     * @param $string
     * @return string
     */
    private function add_header($string){
        return "HTTP/1.1 200 OK\r\nContent-Length: ".strlen($string)."\r\nServer: myPHP\r\nContent-type: application/json\r\n\r\n".$string;
    }
}