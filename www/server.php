<?php
/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午8:24
 */

require_once '../lib/Router.php';

require_once '../lib/App.php';

require_once '../router/test.php';

require_once '../lib/Server.php';

     $route = Router::getInstance();
     $app = App::getInstance();

    $test = test::getInstance();

    $route->get('/',$test,'get');
    $route->get('/test',$test,'get');
    $route->get('/test/:id',$test,'param');

    $app->setRoute($route);

$server = new Server("localhost", 8070,$app);