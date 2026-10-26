<?php
namespace src\model;
use src\config\DB;

class User {
private $db_conn;
public function __construct($conn){
    $this->db_conn = $conn;
}

public function  getUserByEmail($email){
    $sql= 'select * from clients where email = :email limit 1';
    $stmt = $this->db_conn->prepare($sql);
    $stmt->bindParam(':email',$email);
    $stmt->execute();
    return $stmt->fetch();
}
public function createUser($data){
    $sql = 'insert into clients (username,email,password) values (:username,:email,:password)';
    $stmt = $this->db_conn->prepare($sql);
    $stmt->bindParam(':username',$data['username']);
    $stmt->bindParam(':email',$data['email']);
    $stmt->bindParam(':password',$data['password']);
    return $stmt->execute();
}
public function getUserById($id){
$sql = 'select * from clients where id = :id limit 1';
$stmt = $this->db_conn->prepare($sql);
$stmt->bindParam(':id',$id);
$stmt->execute();
return $stmt->fetch();
}
public function getAllUsers($data){
$sql = 'select * from clients';
$stmt = $this->db_conn->prepare($sql);
$stmt->execute();
return $stmt->fetchAll();
}
public function updatePassword($id,$password){
$sql = 'update clients set password = :password where id = :id';
$stmt = $this->db_conn->prepare($sql);
$stmt->bindParam(':id',$id);
$stmt->bindParam(':password',$password);
return $stmt->execute();
}
public function deleteUser($id) {
    $sql = "DELETE FROM clients WHERE id = :id";
    $stmt = $this->db_conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount() > 0;
}
}
?>