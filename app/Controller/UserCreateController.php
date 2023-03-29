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
        if(array_key_exists('firstName',$_POST) && array_key_exists('lastName',$_POST)){

            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];

            if(is_numeric($firstName) || is_numeric($lastName)){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>' Bad Request']);
            }

        }else{

            http_response_code(400);
            return json_encode(['status'=>'400','message'=>' Bad Request']);
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