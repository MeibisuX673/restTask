<?php

namespace app\Controller;

use app\Service\ProductService;

class ProductByIdController
{

    public function __construct(
        ProductService $productService
    ){
        $this->productService = $productService;
    }

    public function __invoke(){

        $id = $_GET['products_id'];

        $product = $this->productService->getById($id);

        if($product != null){

            http_response_code(200);
            return json_encode($product);
        }

        http_response_code(404);
        return json_encode(['message'=>'Product not found']);

    }

}