<?php

namespace app\Controller;

use app\Service\UserService;

class UserController
{
    public  function __construct
    (
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function __invoke()
    {
        $collection = $this->userService->getCollection();

        if(count($collection) > 0){
            return json_encode($collection);
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Product not found']);
    }
}