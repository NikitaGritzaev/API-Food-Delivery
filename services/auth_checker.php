<?php
function auth_handler($auth, $req)
{
    if ($auth) {
        include_once "redis_connect.php";
        $jwt_check = is_jwt_valid($req["jwt"]);
        if ($jwt_check == JWT_INVALID) {
            setHTTPStatus(400);
            exit;
        }
        if ($jwt_check != JWT_VALID) {
            setHTTPStatus(401);
            exit;
        }
    }
}