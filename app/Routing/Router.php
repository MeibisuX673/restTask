<?php

namespace app\Routing;
use app\ReflectionContainer\Container;
use GuzzleHttp\Psr7\Request;

class Router
{
    private array $routs = [];

    public function registerRoute(string $route, string $actionClass, string $method): self{


        if(!array_key_exists($method,$this->routs)){
            $this->routs[$method] = [];
        }

        if(!array_key_exists($route,$this->routs[$method])){
            $this->routs[$method][$route] = [];
        }

        $this->routs[$method][$route] = $actionClass;

        return $this;
    }

    public function resolve(){


        $request = new Request($_SERVER['REQUEST_METHOD'],$_SERVER['REQUEST_URI']);

        $route = $request->getUri()->getPath();

        $method = $request->getMethod();

        $param = explode('/',$route);

        if(array_key_exists(2,$param) && is_numeric($param[2])){
            $this->replaceRoute($request);
        }

        $className = $this->routs[$method][$route] ?? null;

        
        if($className != null){

            $container = new Container();
            $controller = $container->resolveClass($className);

            return $controller();
        }

        http_response_code(404);
        return json_encode(['status'=>'404','message'=>'Route not found']);

    }

    private function replaceRoute(Request $request){

        $path = $request->getUri()->getPath();
        $param = explode('/',$path);
        unset($param[0]);

        $reg = "";
        foreach ($param as $value){

            if(is_numeric($value)){

                $value = "{[a-zA-Z_]+}";
            }
            $reg .= '\/'.$value;

        }

        $reg = '/'.$reg.'/';

        foreach ($this->routs[$request->getMethod()] as $key=>$value){

            $newRoute = preg_replace($reg,$path,$key,-1,$count);

            if($count != 0){
                $oldRoute = $key;
                break;
            }
        }

        $this->routs[$request->getMethod()][$newRoute]=$this->routs[$request->getMethod()][$oldRoute];
        unset($this->routs[$request->getMethod()][$oldRoute]);

        $_GET[$param[1].'_id'] = intval($param[2]);

    }

}