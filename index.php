<?php
include_once "const.php";
include_once "services/routes.php";
include_once "services/handler_checker.php";
include_once "services/auth_checker.php";
include_once "services/body_checker.php";
include_once "services/params_checker.php";
include_once "services/mysqli_handler.php";
include_once "services/requests.php";
include_once "services/headers.php";
include_once "services/logger.php";
include_once "helpers/jwt.php";
include_once "helpers/uuid.php";
date_default_timezone_set("Asia/Novosibirsk");
global $Link, $Log_id, $Redis;
$Log_id = guuid();

header("Content-Type: application/json");

$Link = new MySQLi_Handler(DB_ADDRESS, DB_USER, DB_USER_PASS, DB_NAME);
if ($Link->connect_error) {
    setHTTPStatus(500, "Can't connect to DB: " . mysqli_connect_error());
    exit;
}

$req = get_request();

$handlerObject = $req["handler"];
$handler = $handlerObject["name"];

check_handler($handlerObject);
include_once "routers/" . $handler;

$details = get_details();

auth_handler($details["authorization"], $req);
$req["parameters"] = params_handler($details["params_model"], $req);
body_handler($details["body_model"], $req);

route($req);

$Link->close();
?>