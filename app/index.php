<?php

require_once __DIR__ . '/vendor/autoload.php';

use app\Routing\Router;

header('Content-Type: application/json; charset=utf-8');


$router = new Router();


$router->registerRoute('/products',app\Controller\ProductController::class,'GET')
        ->registerRoute("/products",app\Controller\ProductCreateController::class,'POST')
        ->registerRoute("/products/{id}",\app\Controller\ProductByIdController::class,'GET')
        ->registerRoute("/products/{id}",\app\Controller\ProductPutByIdController::class,'PUT')
        ->registerRoute("/products/{id}",\app\Controller\ProductPatchByIdController::class, 'PATCH')
        ->registerRoute('/brends',app\Controller\BrendController::class,'GET')
        ->registerRoute('/brends/{id}',app\Controller\BrendByIdController::class,'GET')
        ->registerRoute('/brends/{id}/products',app\Controller\BrendsProductCollectionController::class,'GET')
        ->registerRoute('/brends',app\Controller\BrendCreateController::class,'POST')
        ->registerRoute('/brends/{id}',app\Controller\BrendPutByIdController::class,'PUT')
        ->registerRoute('/brends/{id}',app\Controller\BrendPatchByIdController::class,'PATCH')
        ->registerRoute('/users',app\Controller\UserController::class,'GET')
        ->registerRoute('/users/{id}',app\Controller\UserByIdController::class,'GET')
        ->registerRoute('/users',app\Controller\UserCreateController::class,'POST')
        ->registerRoute('/users/{id}',app\Controller\UserPutByIdController::class,'PUT')
        ->registerRoute('/users/{id}',app\Controller\UserPatchByIdController::class,'PATCH')
;


echo $router->resolve();



