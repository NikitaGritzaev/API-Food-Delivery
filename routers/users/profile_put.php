<?php
function route($req) {
    global $Link;
    $body = $req["body"];
    $uuid = get_user_id($req["jwt"]);

    $user = $Link->query("SELECT 1 FROM user WHERE id='$uuid'")->fetch_assoc();
    if (is_null($user)) {
        setHTTPStatus(404, "Can't find iser with id=$uuid in database!");
        exit;
    }

    $changeable = ["fullName", "gender", "address", "birthDate", "phoneNumber"];
    $cur_changeable = [];
    $cur_changeable_value = [];
    foreach ($changeable as $field) {
        if (property_exists($body, $field)) {
            $cur_changeable[] = $field;
            $cur_changeable_value[] = $body->$field;
        }
    }
    $request_string = "UPDATE user SET ";
    foreach ($cur_changeable as $key => $field) {
        $value = $cur_changeable_value[$key];
        $set_string;
        if (is_null($value)) {
            $set_string = "$field = null";
        }
        else {
            if ($field == "birthDate") {
                $date = date_format(new DateTime($cur_changeable_value[$key]), "Y-m-d H:i:s.v");
                $cur_changeable_value[$key] = $date;
            }
            else if ($field == "phoneNumber") {
                $phone =  str_replace([" ", "+", "-", "(", ")"], "", $cur_changeable_value[$key]);
                $cur_changeable_value[$key] = $phone;
            }
            $set_string = "$field = '$cur_changeable_value[$key]'";
        }
        if ($key != count($cur_changeable) - 1) $set_string .= ", ";
        $request_string .= $set_string;
    }
    $request_string .= " WHERE id='$uuid'";
    if (!empty($cur_changeable)) $Link->query($request_string);
    setHTTPStatus(200);
}

function get_details() {
    $body = [
        "fullName" => [
            "required" => true, 
            "handler" => "helpers\user_helpers\\fullName.php", 
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
        "authorization" => true
    ];
}