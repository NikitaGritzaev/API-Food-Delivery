<?php
function generateJWT($user_id, $name)
{
    $secret = "a84bbc643de832c34bc643de8faa5df";
    $header = json_encode(["typ" => "JWT", "alg" => "HS256"]);
    $now = time();
    $payload = json_encode([
        "nameid" => $user_id,
        "name" => $name,
        "email" => $name,
        "nbf" => $now,
        "exp" => $now + 983600,
        "iat" => $now,
        "iss" => API_NAME,
        "aud" => API_NAME
    ]);
    $base64_header = base64url_encode($header);
    $base64_payload = base64url_encode($payload);

    $signature = hash_hmac("sha256", $base64_header . "." . $base64_payload, $secret, true);
    $base64_signature = base64url_encode($signature);

    return $base64_header . "." . $base64_payload . "." . $base64_signature;
}

function is_jwt_valid($jwt)
{
    if (is_null($jwt)) {
        return JWT_NULL;
    }

    if (!preg_match("/^[^\.]+\.[^\.]+\.[^\.]+$/", $jwt)) {
        return JWT_INVALID;
    }
    $Redis = redis_connect();
    if ($Redis->exists($jwt)) {
        return JWT_LOGOUT;
    }

    $secret = "a84bbc643de832c34bc643de8faa5df";
    $tokenParts = explode(".", $jwt);
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);

    if (!$header || !$payload) {
        return JWT_INVALID_SIGNATURE;
    }

    $signature_provided = $tokenParts[2];

    $expiration = json_decode($payload)->exp;
    $is_token_expired = $expiration < time();

    $base64_header = base64url_encode($header);
    $base64_payload = base64url_encode($payload);
    $signature = hash_hmac("sha256", $base64_header . "." . $base64_payload, $secret, true);
    $base64_signature = base64url_encode($signature);

    $is_signature_valid = ($base64_signature === $signature_provided);

    if ($is_token_expired) {
        return JWT_EXPIRED;
    }

    if (!$is_signature_valid) {
        return JWT_INVALID_SIGNATURE;
    }

    return JWT_VALID;
}

function get_user_id($jwt) {
    $tokenParts = explode(".", $jwt);
    $payload = base64_decode($tokenParts[1]);
    $payload = json_decode($payload, true);
    return $payload["nameid"];
}

function base64url_encode($str)
{
    return rtrim(strtr(base64_encode($str), "+/", "-_"), "=");
}