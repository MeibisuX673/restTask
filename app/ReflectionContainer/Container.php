<?php

namespace app\ReflectionContainer;

use ReflectionClass;

class Container{
    public function resolveClass($className){

        $ref = new ReflectionClass($className);
        $constructorClass = $ref->getConstructor();
        $deps =[];

        if($constructorClass != null ){

            $params = $constructorClass->getParameters();

            foreach ($params as $param){
                $name = $param->getType()->getName();
                $deps[] = $this->resolveClass($name);

            }
        }

        return new $className(...$deps);
    }
}