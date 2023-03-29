<?php

namespace app\Controller;

use app\Controller\Traits\getDataVerbPutPatch;
use app\Service\UserService;

class UserPutByIdController
{
    public function __construct
    (
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function __invoke(){

        $id = $_GET['users_id'];

        $user = $this->userService->getById($id);

        if ($user == null) {
            http_response_code(404);
            return json_encode(['status'=>'404','message'=>'User not found']);
        }

        $file = file_get_contents('php://input');

        if(mb_strlen($file)==0){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        $data = $this->getData($file);

        if(!count($data)==2){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        if(!array_key_exists('firstName',$data) || !array_key_exists('lastName',$data)){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        if(mb_strlen($data['firstName'])==0 || mb_strlen($data['lastName'])==0){
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        $updated = $this->userService->setPutById($id,$data['firstName'],$data['lastName']);

        if($updated){
            http_response_code(200);
            return json_encode(['status'=>'200','message'=>'Updated']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'Internal Serverr Error']);
    }

    use getDataVerbPutPatch;
}