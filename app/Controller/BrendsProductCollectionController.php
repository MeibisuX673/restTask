<?php

namespace app\Controller;

use app\Service\BrendService;

class BrendsProductCollectionController
{
    public function __construct
    (
        BrendService $brendService
    )
    {
        $this->brendService = $brendService;
    }

    public function __invoke(){

        $id = $_GET['brends_id'];

        $brend = $this->brendService->getById($id);

        if($brend == null){
            http_response_code(404);
            return json_encode(['status'=>'404','message'=>'Brend not found']);
        }

        $collection = $this->brendService->getCollectionProducts($id);

        if(count($collection) > 0){
            return json_encode($collection);
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Product not found']);
    }

}