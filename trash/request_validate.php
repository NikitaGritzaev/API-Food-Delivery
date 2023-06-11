<?php
function validate_request_method($request, $rule) {
    $methods = ["GET" => 0, "POST" => 1, "PUT" => 2, "DELETE" => 3];
    $method = $request["method"];
    $method_type = $methods[$method];
    $method_rule = $rule[$method_type];
    if ($method_rule == -1) {
        setHTTPStatus(405);
        return false;
    }

    $tokenParts = explode(".", $request["jwt"]);
    $payload = base64_decode($tokenParts[1]);
    $payload = json_decode($payload, true);
    $user_level = is_null($request["jwt"]) ? 0 : $payload["lvl"];
    echo json_encode($payload);
    if ($user_level < $method_rule) {
        setHTTPStatus(403);
        return false;
    }
    return true;
}