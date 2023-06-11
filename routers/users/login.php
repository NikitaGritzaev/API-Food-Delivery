<?php
include_once __DIR__ . "../../../helpers/passwordHash.php";
function route($req)
{
    global $Link;

    $body = $req["body"];
    $pass = $body->password;
    $email = $body->email;


    $user = $Link->query("SELECT * FROM user WHERE email='$email'")->fetch_assoc();
    if (is_null($user)) {
        setHTTPStatus(400, BAD_AUTH);
        exit;
    }

    $salt = $user["salt"];
    $pass = $pass . $salt;
    $passwordHash = hashPass($pass, false)["pass"];

    if ($user["password"] != $passwordHash) {
        setHTTPStatus(400, BAD_AUTH);
        exit;
    }
    setHTTPStatus(200);
    echo json_encode(["token" => generateJWT($user["id"], $email)]);
}

function get_details()
{
    $body = [
        "email" => [
            "required" => true,
            "handler" => "helpers\user_helpers\\email.php",
            "type" => "string"
        ],
        "password" => [
            "required" => true,
            "handler" => "helpers\user_helpers\password.php",
            "type" => "string"
        ],
    ];
    return [
        "params_model" => new stdClass(),
        "body_model" => $body,
        "authorization" => false
    ];
}