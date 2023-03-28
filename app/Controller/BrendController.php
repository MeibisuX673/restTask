<?php

namespace app\Controller;

use app\Service\BrendService;

class BrendController
{
    public function __construct
    (
        BrendService $brendService
    )
    {
        $this->brendService = $brendService;
    }

    public function __invoke(){

        $collection = $this->brendService->getCollection();

        if(count($collection) > 0){
            return json_encode($collection);
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Product not found']);
    }
}