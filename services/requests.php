<?php

function get_addr()
{
    return $_GET["q"] ? rtrim($_GET["q"], "/") : "";
}

function get_body()
{
    return json_decode(file_get_contents("php://input"));
}

function get_method()
{
    return $_SERVER["REQUEST_METHOD"];
}

function get_params()
{
    $params = new stdClass();
    $query  = explode("&", $_SERVER["QUERY_STRING"]);
    foreach ($query as $param) {
       if (strpos($param, "=") === false) $param += "=";
       list($key, $value) = explode("=", $param, 2);
       if ($key == "q") continue;
       $params->$key[] = $value; 
    }
    return $params;
}

function get_jwt()
{
    $header = apache_request_headers()["Authorization"];
    if (is_null($header)) {
        return null;
    }
    if (!preg_match("/^Bearer /", $header)) {
        return false;
    }
    $jwt = substr($header, 7);
    return $jwt ? $jwt : false;
}


function get_handler($addr, $method)
{
    global $Routes;
    $flag = false;
    foreach ($Routes as $key => $route) {
        $pattern = $route["pattern"];
        $handler = $route["handler"];
        if (preg_match($pattern, $addr)) {
            $flag = true;
            if ($route["method"] == $method) return ["name" => $handler, "id" => $key];
        }
    }
    return ["name" => null, "exists" => $flag];
}

function get_request()
{
    $addr = get_addr();
    $method = get_method();
    return [
        "addr" => $addr,
        "handler" => get_handler($addr, $method),
        "steps" => explode("/", $addr),
        "method" => $method,
        "parameters" => get_params(),
        "body" => get_body(),
        "jwt" => get_jwt()
    ];
};