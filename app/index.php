<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\Routing\Router;

header('Content-Type: application/json; charset=utf-8');


$router = new Router();


$router->registerRoute('/products',app\Controller\ProductController::class,'GET')
        ->registerRoute("/products",app\Controller\ProductCreateController::class,"POST")
        ->registerRoute("/products/{id}",\app\Controller\ProductByIdController::class,'GET')
;


echo $router->resolve();



