<?php 
namespace src\controller;
use src\model\User;
use helpers\Response;
use helpers\InputValidator;
class UserController {
private $usermodel;
public function __construct($userModel) {
    $this->usermodel = $userModel;
}
    public function getUserById($id){
        $rules =[
            'id'=>'integer'
        ];
        $validatedata = InputValidator::validate(['id'=>$id],$rules);
        $user = $this->usermodel->getUserById($validatedata['id']);
        if($user){
            Response::jsonResponse(200,[
                "status"=>"success",
                "data"=>$user
            ]);
        }else{
            Response::jsonResponse(404,[
                "status"=>"error",
                "message"=>"User not found"
            ]);
    }
}
        public function getAllUsers($data){
        $users = $this->usermodel->getAllUsers($data);
        if($users){
            Response::jsonResponse(200,[
                "status"=>"success",
                "data"=>$users
            ]);
        }else{
            Response::jsonResponse(404,[
                "status"=>"error",
                "message"=>"No users found"
            ]);
    }
}
        public function updatePassword($id,$data){
        $rules =[
            'id'=>'integer',
            'password'=>'string'
        ];
        $validatedata = InputValidator::validate(array_merge(['id'=>$id],$data),$rules);
        $validatedata['password'] = password_hash($validatedata['password'],PASSWORD_DEFAULT);
        $user = $this->usermodel->updatePassword($validatedata['id'],$validatedata['password']);
        if($user){
            Response::jsonResponse(200,[
                "status"=>"success",
                "message"=>"Password updated successfully"
            ]);
        }else{
            Response::jsonResponse(404,[
                "status"=>"error",
                "message"=>"User not found"
            ]);
        }}

        public function deleteUser($id){
        $user= $this->usermodel->deleteUser($id);
        if($user){
            Response::jsonResponse(200,[
            "status" => "success",
            "message"=> "user deleted successfuly"
            ]);
        }else{
            Response::jsonResponse(400,[
            "status" => "error",
            "message"=> "user not found"
            ]);
        }
        }
}
?>