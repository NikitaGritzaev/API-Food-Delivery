<?php
include_once __DIR__ . "../../../helpers/passwordHash.php";
function route($req)
{
    $body = $req["body"];
    $pass = $body->password;
    $email = $body->email;
    $birthDate = null;
    if (!is_null($body->birthDate)) {
        $birthDate = date_format(new DateTime($body->birthDate), "Y-m-d H:i:s.v");
    }
    $gender = $body->gender;
    $fullName = $body->fullName;
    $phoneNumber = str_replace([" ", "+", "-", "(", ")"], "", $body->phoneNumber);
    $address = $body->address;
    $passwordHash = hashPass($pass);
    $password = $passwordHash["pass"];
    $salt = $passwordHash["salt"];

    global $Link;

    $checkEmail = $Link->query("SELECT 1 FROM user WHERE email='$email'")->fetch_assoc();
    if (!is_null($checkEmail)) {
        setHTTPStatus(409);
        echo json_encode([
            "title" => "User with such email already exists!",
            "errors" => [
                "email" => ["Duplicate email"]
            ]
        ]);
        exit;
    }

    $uuid = guuid();
    $Link->query(
        "INSERT INTO 
        user(id, email, fullName, password, salt, birthDate, gender, phoneNumber, address)
        VALUES
        (
            '$uuid',
            '$email',
            '$fullName',
            '$password',
            '$salt',
            NULLIF('$birthDate' , ''),
            '$gender',
            NULLIF('$phoneNumber', ''),
            NULLIF ('$address', '')
        )"
    );
    
    setHTTPStatus(200);
    echo json_encode(["token" => generateJWT($uuid, $email)]);
}

function get_details() {
    $body = [
        "fullName" => [
            "required" => true,
            "handler" => "helpers\user_helpers\\fullName.php",
            "type" => "string"
        ],
        "password" => [
            "required" => true, 
            "handler" => "helpers\user_helpers\password.php", 
            "type" => "string"
        ],
        "email" => [
            "required" => true, 
            "handler" => "helpers\user_helpers\\email.php", 
            "type" => "string"
        ],
        "gender" => [
            "required" => true, 
            "handler" => "helpers\user_helpers\gender.php", 
            "type" => "string"
        ],
        "address" => [
            "required" => false, 
            "handler" => "helpers\user_helpers\address.php", 
            "type" => "string"
        ],
        "birthDate" => [
            "required" => false, 
            "handler" => "helpers\user_helpers\birthDate.php", 
            "type" => "string"
        ],
        "phoneNumber" => [
            "required" => false, 
            "handler" => "helpers\user_helpers\phoneNumber.php", 
            "type" => "string"
        ]
    ];
    return [
        "params_model" => new stdClass(),
        "body_model" => $body,
        "authorization" => false
    ];
}

