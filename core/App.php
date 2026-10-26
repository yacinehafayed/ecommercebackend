<?php
namespace src\core;
use src\core\Database;
use src\controller\AuthController;
use src\controller\UserController;
use src\controller\ProductController;
use src\model\User;
use src\model\Product;
class App {
    public static function intialiaseResources($controllerClassName) {
        $db_config = require __DIR__ . '/../config/DB.php';
        $database = new Database($db_config);
        $conn = $database->getConnection();
        // 1. Pass the active connection to the models
        $userModel = new User($conn);
        $productModel = new Product($conn);
        // 2. Return the requested controller injected with its assigned model
        switch ($controllerClassName) {
            case UserController::class:
                return new UserController($userModel);
            case AuthController::class:
                return new AuthController($userModel);
            case ProductController::class:
                return new ProductController($productModel);
            default:
                throw new Exception("Controller {$controllerClassName} not found in container.");
        }
    }
}
?>