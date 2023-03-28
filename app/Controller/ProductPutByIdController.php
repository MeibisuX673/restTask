<?php

namespace app\Controller;



use app\Controller\Traits\getDataVerbPutPatch;
use app\Service\ProductService;
use GuzzleHttp\Psr7\Request;

class ProductPutByIdController
{
    public function __construct(
        ProductService $productService
    )
    {
        $this->productService = $productService;
    }

    public function __invoke()
    {

        $id = $_GET['products_id'];

        $file = file_get_contents('php://input');

        if($this->productService->getById($id) == null){
            http_response_code(404);
            return json_encode(['status'=>'404','message'=>'Product not found']);
        }

        if(mb_strlen($file)==0){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad 1Request']);
        }

        $data = $this->getData($file);

        if(count($data)>1){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        if(!array_key_exists('name',$data)){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        if(mb_strlen($data['name'])==0){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        $updated = $this->productService->setPutById($id,$data['name']);

        if($updated){
            http_response_code(200);
            return json_encode(['status'=>'200','message'=>'Updated']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'Internal Serverr Error']);
    }

    use getDataVerbPutPatch;
}