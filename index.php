<?php
//Show Errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Force HTTPS
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $redirect);
exit();
}

//Start sesssion
session_start();

//Utilities
include('resources/utils.php');

//Require the Router and Templating Engine
require_once 'Xesau/Router.php';
require_once 'SimpleTemplateEngine/loader.php';

//Instantiate the objects
use Xesau\Router;
$env = new SimpleTemplateEngine\Environment('views', '.php');	//views folder and default extension

//Router code starts here

$router = new Router(function ($method, $path, $statusCode, $exception) {
	global $env;	//bring in the global template engine
    echo $env->render('404');
}); 

$router->get('/', function() {
	global $env;	//bring in the global template engine
	echo $env->render('apps');
});

$router->get('/apps', function() {
	global $env;	//bring in the global template engine
	echo $env->render('apps');
});

$router->get('/editor', function() {
	global $env;	//bring in the global template engine
	echo $env->render('apps');
});

$router->get('/page/(.*)', function($page) {
	global $env;	//bring in the global template engine
	echo $env->render('test', ['page'=>$page]);
});

$router->route(['OPTION', 'PUT'], '/test', 'PageController::test');

$router->dispatchGlobal();