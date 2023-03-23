<?php

namespace app\Controller;


use app\Entity\Product;
use app\Service\ProductService;

class ProductCreateController
{

    public function __construct(
        ProductService  $productService,
    ){
        $this->productService = $productService;
    }

    public function __invoke(){

        if(array_key_exists('name',$_POST)){

            $name = $_POST['name'];
        }

        if(mb_strlen($name)==0){

            http_response_code(400);
            return json_encode(['status'=>'400','message'=>' Bad Request']);
        }

        $created = $this->productService->create($name);

        if($created){
            http_response_code(201);
            return json_encode(['status'=>"201",'message'=>'Created']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'No created']);
    }
}