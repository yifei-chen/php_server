<?php
/**
 * Created by PhpStorm.
 * User: CYF
 * Date: 16/7/27
 * Time: 下午8:24
 */

require_once 'lib/Router.php';

require_once 'lib/App.php';

require_once 'router/test.php';

$route = Router::getInstance();

$app = App::getInstance();

$route->get('/',test::getInstance(),'get')
        ->get('/test',test::getInstance(),'get');

$app->setRoute($route);

$app->run();