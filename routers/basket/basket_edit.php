<?php
include_once "helpers\dish_helpers\dish_checker.php";
function route($req) {
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $dish_id = $req["steps"][3];

    check_dish($dish_id);

    $check = $Link->query(
        "SELECT amount FROM basket
        WHERE user='$user_id' AND dish='$dish_id'"
    )->fetch_assoc();

    if (is_null($check)) {
        setHTTPStatus(404, "Dish with id=$dish_id not in basket");
        exit;
    }

    $increase = $req["parameters"]["increase"];
    if ($increase == "true" && $check["amount"] > 1) {
        $Link->query(
            "UPDATE basket set amount=amount-1
            WHERE user='$user_id' AND dish='$dish_id'"
        );
    }
    else {
        $Link->query("DELETE FROM basket WHERE user='$user_id' AND dish='$dish_id'");
    }
    setHTTPStatus(200);
}

function get_details()
{
    $params_model = [
        "increase" => ["type" => "bool", "default" => "false", "many" => false]
    ];

    return [
        "params_model" => $params_model,
        "body_model" => new stdClass(),
        "authorization" => false
    ];
}