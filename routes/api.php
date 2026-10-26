<?php
use src\controller\AuthController;
use src\controller\UserController;
use src\controller\ProductController;
use src\core\router;
//use router functions get(),post().. to store endpoints uri and appropriate controller in routes[]
//GET,POST,PUT... structure are defined in router class
//GET router
$router->GET("/api/user/{id}",[UserController::class,"getUserById"]);
$router->GET("/api/users",[UserController::class,"getAllUsers"]);
$router->GET("/api/products",[ProductController::class,"getAllProducts"]);
//POST router
$router->POST("/api/login",[AuthController::class,"login"],[
    "email" => "string",
    "password"=>"string"
]);
$router->POST("/api/register",[AuthController::class,"register"],[
    "username" => "string",
    "email" => "string",
    "password" => "string"
]);
$router->POST("/api/products",[ProductController::class,"createProduct"],[
    "name" => "string",
    "description"=>"string",
    "price" => "float",
    "category" => "string"
]);
//PATCH router
$router->PATCH("/api/users/{id}/UpdatePassword", [UserController::class, "updatePassword"], []);
//PUT router
//DELETE router
$router->DELETE("/api/deleteproduct/{id}",[ProductController::class,"deleteProduct"]);
$router->DELETE("/api/deleteUser/{id}",[UserController::class,"deleteUser"]);
?>