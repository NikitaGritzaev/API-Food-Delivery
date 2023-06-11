<?php
include_once "helpers\dish_helpers\dish_checker.php";
function route($req) {
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $dish_id = $req["steps"][2];
    $dish = check_dish($dish_id);

    $check = $Link->query(
        "SELECT 1 FROM user_dish
        WHERE user='$user_id' AND dish='$dish_id'"
    );
    if ($check->num_rows === 0) {
        setHTTPStatus(400, "User cannot rate a dish that he did not order!");
    }
    else {
        $score = $req["parameters"]["ratingScore"];
        $Link->query(
            "UPDATE user_dish SET rating=$score
            WHERE user='$user_id' AND dish='$dish_id'"
        );
        setHTTPStatus(200);
    }

}

function get_details() {
    $params_model = [
        "ratingScore" => [
            "type" => "int",
            "regex" => "/^(10|[0-9])$/",
            "default" => "0", "many" => false
        ]
    ];
        
    return [
        "params_model" => $params_model,
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}