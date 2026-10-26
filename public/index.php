<?php
// It handles all requests and routes them to the appropriate controller
//whatever the request is asking for, this page is always the first page will execute in the execution engine
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
use src\core\Router;
$dotenv = Dotenv::createImmutable(__DIR__ . '/..'); //pass path into DOTENV static method as param
$dotenv->load(); //keys into memory env variables
$uri = $_SERVER['REQUEST_URI']; // uri that client typed
$method = $_SERVER['REQUEST_METHOD']; // method that client used
$router = new Router(); // instance of router
require_once '../routes/api.php'; //routes associative multi dim array (hashmap) contains all endpoints
$router->DispatchRouteToEndPoint($uri,$method);
?>