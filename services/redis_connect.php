<?php
function redis_connect() {
    require "predis-main/predis-main/autoload.php";
    try {
        global $Redis;
        Predis\Autoloader::register();
        $Redis = new Predis\Client();
        $Redis->connect();
        return $Redis;
    } catch(Predis\Connection\ConnectionException $e) {
        setHTTPStatus(500, $e->getMessage());
    }
    

}