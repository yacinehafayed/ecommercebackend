<?php
namespace src\core;
use src\core\App;
use helpers\InputValidator;
use helpers\Response;

class Router {
// all routes will stored in this variable
private $routes = [];
// function to add route in routes array,it used by other functions
public function AddRouteToRoutes($method,$uri,$controller,$rules){
    $this->routes[$method][] = [
        "uri" => $uri ,
        "controller" => $controller ,
        "rules" => $rules
    ];
}
public function GET($uri,$controller,$rules=null){$this->AddRouteToRoutes(
    "GET",$uri,$controller,$rules
);}
public function POST($uri,$controller,$rules=null){$this->AddRouteToRoutes(
    "POST",$uri,$controller,$rules
);}
public function PUT($uri,$controller,$rules=null){$this->AddRouteToRoutes(
    "PUT",$uri,$controller,$rules
);}
public function PATCH($uri,$controller,$rules=null){$this->AddRouteToRoutes(
    "PATCH",$uri,$controller,$rules
);}
public function DELETE($uri,$controller,$rules=null){$this->AddRouteToRoutes(
    "DELETE",$uri,$controller,$rules
);}

private function convertPathToRegex($path): string {
$regex = preg_replace("#\{\w+\}#", "(\d+)", $path);
return "#^" . $regex . "/?$#"; }
public function DispatchRouteToEndPoint($uri,$method){
    // 1. Get only the path (/users/15)
    $uri = parse_url($uri, PHP_URL_PATH);
    // 2. Check if this HTTP method exists
    if (!isset($this->routes[$method])) {
        Response::jsonResponse(404, [
            "status" => "error",
            "message" => "HTTP method not allowed"
        ]);
        return;
    }
    // 3. Search through all routes of this method
    foreach ($this->routes[$method] as $route) {
        // Convert "/users/{id}" -> "#^/users/(\d+)/?$#"
        $regex = $this->convertPathToRegex($route["uri"]);
        // Skip if it doesn't match
        if (!preg_match($regex, $uri, $matches)) {
            continue;
        }
        // Remove the full matched uri by removing index 0 "/api/user/43" and keeping only index 1 "parameters"
        array_shift($matches);
        // 5. Create controller
        $controllerClass = $route["controller"][0];
        $controllerMethod = $route["controller"][1];
        $controller = App::intialiaseResources($controllerClass);
        // 6. Route parameters
        $args = $matches;
        // 7. Read request body payload
        $body = json_decode(file_get_contents("php://input"), true);
        // 8. Validate body
        if ($route["rules"]) {
            $body = InputValidator::validate(
                $body,
                $route["rules"]
            );
        }
        // Add request body as last parameter
        $args[] = $body;
        // 9. Execute controller method
        call_user_func_array(
            [$controller, $controllerMethod],
            $args
        );
        return;
    }
    // 10. No matching route
    Response::jsonResponse(404, [
        "status" => "error",
        "message" => "URL not found"
    ]);
}

}
?>