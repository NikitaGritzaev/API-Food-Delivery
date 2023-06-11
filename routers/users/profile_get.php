<?php
include_once __DIR__ . "../../../helpers/date8601.php";

function route($req) {
    global $Link;
    $uuid = get_user_id($req["jwt"]);
    
    $user = $Link->query("SELECT * FROM user WHERE id='$uuid'")->fetch_assoc();
    if (is_null($user)) {
        setHTTPStatus(404, "Can't find iser with id=$uuid in database!");
        exit;
    }

    setHTTPStatus(200);
    echo json_encode([
        "fullName" => $user["fullName"],
        "birthDate" => dtime_to_iso_8601($user["birthDate"]),
        "gender" => $user["gender"],
        "address" => $user["address"],
        "email" => $user["email"],
        "phoneNumber" => $user["phoneNumber"],
        "id" => $user["id"]
    ]);

}

function get_details() {
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}