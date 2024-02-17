<?php

class CFrontController
{

    public function run($path){

        $method = $_SERVER['REQUEST_METHOD'];

        if (strpos($path, '?') != false){
            $url = explode('?', $path);
            $resource = explode('/', $url[0]);
            $params = explode('&', $url[1]);
        } else {
            $resource = explode('/', $path);
        }

        array_shift($resource);
        array_shift($resource);

        if ($resource[0] != 'api'){

            $controller = 'C' . $resource[0];
            $dir = 'CONTROL';
            $elementDir = scandir($dir);

            if (in_array($controller . ".php", $elementDir)) {
                if (isset($resource[1])) {
                    $function = $resource[1];
                    if (method_exists($controller, $function)) {

                        $param =array();

                        if (isset($url[1])){
                            for ($i = 0; $i < count($params); $i++){
                                $array = explode('=', $params[$i]);
                                $param = $array[1];
                            }
                            $controller::$function($param);
                        } else {
                            for ($i = 2; $i < count($resource); $i++) {
                                $param[] = $resource[$i];
                            }
                            $num = (count($param));

                            if ($num == 0) $controller::$function();
                            else if ($num == 1) $controller::$function($param[0]);
                            else if ($num == 2) $controller::$function($param[0], $param[1]);
                        }



                    }
                    else {
                        if (CUtente::isLogged()){
                                CHome::blogHome();
                            }
                            CHome::blogHome();
                        }
                    }
                } else {
                    if (CUtente::isLogged()){

                            CHome::blogHome();
                    } else {
                        CHome::blogHome();
                    }
                }
        } else {
            if (CUtente::isLogged()){

                    CHome::blogHome();

            } else {
                CHome::blogHome();
            }
        }
    }


}
