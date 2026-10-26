<?php
namespace src\controller ;

use src\model\User;
use helpers\Response;
use helpers\InputValidator;
class AuthController {
private $usermodel;
public function __construct($userModel){
        $this->usermodel = $userModel;
    }
    // POST /api/login
    public function login($data){
        // logic to handle login
    $rules =[
        'email'=>'email',
        'password'=>'string'
    ];
    $validatedata = InputValidator::validate($data,$rules);
    $user = $this->usermodel->getUserByEmail($validatedata['email']); // Fetch user from database by email
    if($user && password_verify($validatedata['password'],$user['password'])){
        Response::jsonResponse(200,[
            "status"=>"success",
            "message"=>"User logged in successfully"
        ]);
    } else {
        Response::jsonResponse(401,[
            "status"=>"error",
            "message"=>"Invalid email or password"
        ]);
    }
    }
    //POST /api/register
    public function register($data) {
        // logic to handle registration
    $rules =[
        'username'=>'string',
        'email'=>'email',
        'password'=>'string'
    ];
    $validatedata = InputValidator::validate($data,$rules);
    $validatedata['password'] = password_hash($validatedata['password'],PASSWORD_DEFAULT);
    if($this->usermodel->createUser($validatedata)){
        Response::jsonResponse(201,[
            "status"=>"success",
            "message"=>"User registered successfully"
        ]);}else{
            Response::jsonResponse(500,[
                "status"=>"error",
                "message"=>"User registration failed"
            ]);
        }
    }
}

?>