<?php

namespace app\Controller;

use app\Controller\Traits\getDataVerbPutPatch;
use app\Service\UserService;

class UserPatchByIdController
{
    public function __construct
    (
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function __invoke()
    {

        $id = $_GET['users_id'];
        $trueKeys = ['firstName','lastName'];

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

        $count = count($data);
        if($count < 1 || $count > 2) {
            http_response_code(400);
            return json_encode(['status'=>'400','message'=>'Bad Request']);
        }

        foreach ($data as $key=>$value){

            if(!in_array($key,$trueKeys)){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>'Bad Request']);
            }

            if(mb_strlen($value)==0){
                http_response_code(400);
                return json_encode(['status'=>'400','message'=>'Bad Request']);
            }
        }

        $updated = $this->userService->setPatchById($id,$data);

        if($updated){
            http_response_code(200);
            return json_encode(['status'=>'200','message'=>'Updated']);
        }

        http_response_code(500);
        return json_encode(['status'=>'500','message'=>'Internal Serverr Error']);


    }

    use getDataVerbPutPatch;

}