<?php

namespace app\Controller;

use app\Service\UserService;

class UserByIdController
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

        $product = $this->userService->getById($id);

        if($product != null){

            http_response_code(200);
            return json_encode($product);
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Product not found']);
    }
}