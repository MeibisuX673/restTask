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

        $param = explode('/',$route);
        if(array_key_exists(2,$param) && is_numeric($param[2])){
            $this->replaceRoute($request);
        }

        $method = $request->getMethod();

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

        foreach ($this->routs[$request->getMethod()] as $key=>$value){
            preg_match("/\/$param[1]\/{[a-zA-Z]+}/",$key,$matches);
        }

        $newRoute = preg_replace('/{[a-zA-Z]+}/',$param[2],$matches[0]);

        $this->routs[$request->getMethod()][$newRoute]=$this->routs[$request->getMethod()][$matches[0]];
        unset($this->routs[$request->getMethod()][$matches[0]]);

        $_GET[$param[1].'_id'] = intval($param[2]);

    }

}