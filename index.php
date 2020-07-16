<?php
//Show Errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Require the Router and Templating Engine
require_once 'Xesau/Router.php';
require_once 'SimpleTemplateEngine/loader.php';

//Instantiate the objects
use Xesau\Router;
$env = new SimpleTemplateEngine\Environment('views', '.php');	//views folder and default extension

//Router code starts here

$router = new Router(function ($method, $path, $statusCode, $exception) {
    http_response_code($statusCode);
    include 'views/error.html';
}); 

$router->get('/', function() {
    // Home page
    include 'views/home.html';
});

$router->get('/page/(.*)', function($page) {
	global $env;	//bring in the global template engine
	echo $env->render('test', ['page'=>$page]);
});

$router->route(['OPTION', 'PUT'], '/test', 'PageController::test');

$router->dispatchGlobal();