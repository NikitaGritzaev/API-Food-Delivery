<?php
include_once "helpers\dish_helpers\dish_checker.php";
function route($req) {
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $dish_id = $req["steps"][2];
    check_dish($dish_id);

    $check = $Link->query(
        "SELECT 1 FROM user_dish
        WHERE user='$user_id' AND dish='$dish_id'"
    );

    setHTTPStatus(200);
    if ($check->num_rows === 0) {
        echo json_encode(false);
    }
    else {
        echo json_encode(true);
    }

}

function get_details() {
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}