<?php

namespace app\Controller;

use app\Service\BrendService;

class BrendCreateController
{

    public function __construct(
        BrendService $brendService
    )
    {
        $this->brendService = $brendService;
    }

    public function __invoke()
    {

        if(array_key_exists('name',$_POST)) {
            $name = $_POST['name'];
            if(mb_strlen($name)==0){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>'Bad request']);
            }
        }

        $crated = $this->brendService->create($name);

        if($crated){
            http_response_code(201);
            return json_encode(['status'=>'201','message'=>'Created']);
        }
        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'No created']);


    }
}