<?php

class Route 
{
    private $pre;

    public function __construct($pre = "")
    {
        $this->pre = $pre;
    }

    public static function get($route, callable $func)
    {        
        if (isset($GLOBALS['route_executed'])) {
            die;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        if (isset($GLOBALS['prefix'])) {
            $route = $GLOBALS['prefix'] . $route;
        }

        removeSlashesFromStartAndEnd($route);

        $uri = substr($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], strlen(HOST_URI));
        
        // clear $uri from 'get' part if exists
        if (strpos($uri, '?')) {
            $uri = explode('?', $uri)[0];
        } 

        removeSlashesFromStartAndEnd($uri);

        // explode $uri
        $arr_uri = explode('/', $uri);

        // explode $route
        $arr_route = explode('/', $route);
        
        // escape this route if amount of slash-sections is different
        if (count($arr_uri) != count($arr_route)) {
            return;
        }

        // associated array with arguments
        $get_from_uri = [];

        // compare sections of $uri and $route
        foreach ($arr_uri as $key => $uri_chunk) {
            // if $route element with braces (element is variable)
            if (strpos($arr_route[$key], '{') !== false) {
                $var_name = substr($arr_route[$key], 1, -1);
                $get_from_uri[$var_name] = $uri_chunk;
                // $var_name = substr($arr_route[$key], strpos($arr_route[$key], '{') + 1, strpos($arr_route[$key], '}') - 1);
                // $get_from_uri[$var_name] = substr($uri_chunk, strpos($arr_route[$key], '{'));
            } else {
                // if $route element without braces (usual word)
                if ($arr_route[$key] != $uri_chunk) {
                    return;
                }
            }
        }

        // if all is OK, launch callback function
        call_user_func_array($func, $get_from_uri);

        // say to program not to execute next routes
        $GLOBALS['route_executed'] = true;
    }

    public static function post($route, callable $func)
    {        
        if (isset($GLOBALS['route_executed'])) {
            die;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($GLOBALS['prefix'])) {
            $route = $GLOBALS['prefix'] . $route;
        }

        removeSlashesFromStartAndEnd($route);

        $uri = substr($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], strlen(HOST_URI));

        // clear $uri from 'get' part if exists
        if (strpos($uri, '?')) {
            $uri = explode('?', $uri)[0];
        } 

        removeSlashesFromStartAndEnd($uri);

        // explode $uri
        $arr_uri = explode('/', $uri);

        // explode $route
        $arr_route = explode('/', $route);
        
        // escape this route if amount of slash-sections is different
        if (count($arr_uri) != count($arr_route)) {
            return;
        }

        // associated array with arguments
        $get_from_uri = [];

        // compare sections of $uri and $route
        foreach ($arr_uri as $key => $uri_chunk) {
            // if $route element with braces (element is variable)
            if (strpos($arr_route[$key], '{') !== false) {
                $var_name = substr($arr_route[$key], 1, -1);
                $get_from_uri[$var_name] = $uri_chunk;
                // $var_name = substr($arr_route[$key], strpos($arr_route[$key], '{') + 1, strpos($arr_route[$key], '}') - 1);
                // $get_from_uri[$var_name] = substr($uri_chunk, strpos($arr_route[$key], '{'));
            } else {
                // if $route element without braces (usual word)
                if ($arr_route[$key] != $uri_chunk) {
                    return;
                }
            }
        }

        // if all is OK, launch callback function
        call_user_func_array($func, $get_from_uri);

        // say to program not to execute next routes
        $GLOBALS['route_executed'] = true;
    }

    public static function prefix($route)
    {
        if (isset($GLOBALS['route_executed'])) {
            die;
        }
        
        return new Route($route);
    }

    public function group(callable $func)
    {
        $GLOBALS['prefix'] = $this->pre;
        $func();
        unset($GLOBALS['prefix']);
    }

}