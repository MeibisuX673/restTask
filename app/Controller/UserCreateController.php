<?php

namespace app\Controller;

use app\Service\UserService;

class UserCreateController
{

    public function __construct
    (
        UserService  $userService
    )
    {
        $this->userService = $userService;
    }

    public function __invoke()
    {
        if(array_key_exists('first_name',$_POST) && array_key_exists('last_name',$_POST)){

            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];

            if(is_numeric($firstName) || is_numeric($lastName)){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>' Bad Request']);
            }

        }

        if(mb_strlen($firstName)==0 && mb_strlen($lastName)==0){

            http_response_code(400);
            return json_encode(['status'=>'400','message'=>' Bad Request']);
        }

        $created = $this->userService->create($firstName,$lastName);

        if($created){
            http_response_code(201);
            return json_encode(['status'=>"201",'message'=>'Created']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'No created']);
    }


}