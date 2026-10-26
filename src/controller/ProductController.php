<?php
namespace src\controller;
use src\model\Product;
use helpers\Response;
use helpers\InputValidator;

class ProductController {
    private $productmodel;
    public function __construct($productModel){
        $this-> productmodel = $productModel;
    }
    public function getAllProducts(){
        $products = $this-> productmodel->getAllProducts();
        if($products ){
            Response::jsonResponse(200,[
                "status"=>"success",
                "data"=>$products
            ]);
        }else{
            Response::jsonResponse(404,[
                "status"=>"error",
                "message"=>"No products found"
            ]);
        }
    }
    public function createProduct($data){
        $rules = [
            'name'=>'string',
            'description'=>'string',
            'price'=>'numeric',
            'category'=>'string'
        ];
        $validatedata = InputValidator::validate($data,$rules);
        $product = $this-> productmodel->createProduct($validatedata);
        if($product){
            Response::jsonResponse(200,[
                "status" => "success",
                "data" => $product
            ]);
        }else {
            Response::jsonResponse(400,[
            "status" => "error",
            "message" => "can't create this product"
            ]);
        }
    }

    public function deleteProduct($id){
    $product = $this->productmodel->deleteProductById($id);
    if ($product) {
        Response::jsonResponse(200, [
            "status" => "success",
            "message" => "Product with ID $id has been deleted."
        ]);
    } else {
        Response::jsonResponse(404, [
            "status" => "error",
            "message" => "Product not found or could not be deleted."
        ]);
    }
    }
}

?>