<?php

namespace app\Controller;

use app\Service\ProductService;
use GuzzleHttp\Psr7\Request;
use PDO;

class ProductController
{

    public function __construct(
        ProductService $productService,
    ){
        $this->productService = $productService;
    }

    public function __invoke()
    {

        $collection = $this->productService->getCollection();

        if(count($collection) > 0){
            return json_encode($collection);
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Product not found']);
    }


}