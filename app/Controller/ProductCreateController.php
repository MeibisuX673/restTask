<?php

namespace app\Controller;


use app\Service\ProductService;

class ProductCreateController
{

    public function __construct(
        ProductService  $productService,
    ){
        $this->productService = $productService;
    }

    public function __invoke(){

        if(array_key_exists('name',$_POST) && array_key_exists('brend',$_POST)){

            if(!is_numeric($_POST['brend'])){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>' Bad Request']);
            }

        }else{
            http_response_code(400);
            return json_encode(['message'=>' Bad Request']);
        }

        $name = $_POST['name'];
        $brendid = $_POST['brend'];

        if(mb_strlen($name)==0){

            http_response_code(400);
            return json_encode(['status'=>'400','message'=>' Bad Request']);
        }

        $created = $this->productService->create($name,$brendid);

        if($created){
            http_response_code(201);
            return json_encode(['status'=>"201",'message'=>'Created']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'No created']);
    }
}