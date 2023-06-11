<?php
function check_handler($handlerObject)
{
    $handler = $handlerObject["name"];
    if (is_null($handler)) {
        if ($handlerObject["exists"] == false) {
            setHTTPStatus(404);
        } else {
            setHTTPStatus(405);
        }
        exit;
    }
    
    $handlerFile = realpath(dirname(__FILE__)) . "\\..\\routers\\" . $handler;
    if (!file_exists($handlerFile)) {
        setHTTPStatus(500, "Can't find handler for request");
        exit;
    }
}